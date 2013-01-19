<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Generator;

use Eloquent\Cosmos\ClassName;
use Eloquent\Typhoon\Configuration\RuntimeConfiguration;
use Eloquent\Typhoon\TypeCheck\TypeCheck;
use Icecave\Pasta\AST\Expr\ArrayLiteral;
use Icecave\Pasta\AST\Expr\Assign;
use Icecave\Pasta\AST\Expr\Call;
use Icecave\Pasta\AST\Expr\Concat;
use Icecave\Pasta\AST\Expr\Constant;
use Icecave\Pasta\AST\Expr\Literal;
use Icecave\Pasta\AST\Expr\LogicalAnd;
use Icecave\Pasta\AST\Expr\LogicalNot;
use Icecave\Pasta\AST\Expr\Member;
use Icecave\Pasta\AST\Expr\NewOperator;
use Icecave\Pasta\AST\Expr\QualifiedIdentifier;
use Icecave\Pasta\AST\Expr\StaticMember;
use Icecave\Pasta\AST\Expr\StrictEquals;
use Icecave\Pasta\AST\Expr\StrictNotEquals;
use Icecave\Pasta\AST\Expr\Subscript;
use Icecave\Pasta\AST\Expr\Variable;
use Icecave\Pasta\AST\Func\ArrayTypeHint;
use Icecave\Pasta\AST\Func\ObjectTypeHint;
use Icecave\Pasta\AST\Func\Parameter;
use Icecave\Pasta\AST\Identifier;
use Icecave\Pasta\AST\PhpBlock;
use Icecave\Pasta\AST\Stmt\ExpressionStatement;
use Icecave\Pasta\AST\Stmt\IfStatement;
use Icecave\Pasta\AST\Stmt\NamespaceStatement;
use Icecave\Pasta\AST\Stmt\ReturnStatement;
use Icecave\Pasta\AST\SyntaxTree;
use Icecave\Pasta\AST\Type\AccessModifier;
use Icecave\Pasta\AST\Type\ClassDefinition;
use Icecave\Pasta\AST\Type\ClassModifier;
use Icecave\Pasta\AST\Type\ConcreteMethod;
use Icecave\Pasta\AST\Type\Property;
use Icecave\Rasta\Renderer;

class FacadeGenerator implements StaticClassGenerator
{
    /**
     * @param Renderer|null                      $renderer
     * @param RuntimeConfigurationGenerator|null $configurationGenerator
     */
    public function __construct(
        Renderer $renderer = null,
        RuntimeConfigurationGenerator $configurationGenerator = null
    ) {
        $this->typeCheck = TypeCheck::get(__CLASS__, func_get_args());

        if (null === $renderer) {
            $renderer = new Renderer;
        }
        if (null === $configurationGenerator) {
            $configurationGenerator = new RuntimeConfigurationGenerator;
        }

        $this->renderer = $renderer;
        $this->configurationGenerator = $configurationGenerator;
    }

    /**
     * @return Renderer
     */
    public function renderer()
    {
        $this->typeCheck->renderer(func_get_args());

        return $this->renderer;
    }

    /**
     * @return RuntimeConfigurationGenerator
     */
    public function configurationGenerator()
    {
        $this->typeCheck->configurationGenerator(func_get_args());

        return $this->configurationGenerator;
    }

    /**
     * @param RuntimeConfiguration $configuration
     * @param null                 &$className
     *
     * @return string
     */
    public function generate(
        RuntimeConfiguration $configuration,
        &$className = null
    ) {
        $this->typeCheck->generate(func_get_args());

        return $this->generateSyntaxTree(
            $configuration,
            $className
        )->accept($this->renderer());
    }

    /**
     * @param RuntimeConfiguration $configuration
     * @param null                 &$className
     *
     * @return SyntaxTree
     */
    public function generateSyntaxTree(
        RuntimeConfiguration $configuration,
        &$className = null
    ) {
        $this->typeCheck->generateSyntaxTree(func_get_args());

        $className = $configuration
            ->validatorNamespace()
            ->joinAtoms('TypeCheck')
        ;

        $classDefinition = new ClassDefinition(
            new Identifier($className->shortName()->string()),
            ClassModifier::ABSTRACT_()
        );
        $classDefinition->add($this->generateGetMethod());
        $classDefinition->add($this->generateInstallMethod());
        $classDefinition->add($this->generateSetRuntimeGenerationMethod());
        $classDefinition->add($this->generateRuntimeGenerationMethod());
        $classDefinition->add($this->generateCreateValidatorMethod(
            $configuration
        ));
        $classDefinition->add($this->generateDefineValidatorMethod(
            $configuration
        ));
        $classDefinition->add($this->generateConfigurationMethod(
            $configuration
        ));

        $instancesProperty = new Property(
            new Identifier('instances'),
            AccessModifier::PRIVATE_(),
            true
        );
        $instancesProperty->setDefaultValue(
            new ArrayLiteral
        );
        $classDefinition->add($instancesProperty);

        $dummyModeProperty = new Property(
            new Identifier('dummyMode'),
            AccessModifier::PRIVATE_(),
            true
        );
        $dummyModeProperty->setDefaultValue(new Literal(false));
        $classDefinition->add($dummyModeProperty);

        $runtimeGenerationProperty = new Property(
            new Identifier('runtimeGeneration'),
            AccessModifier::PRIVATE_(),
            true
        );
        $runtimeGenerationProperty->setDefaultValue(new Literal(false));
        $classDefinition->add($runtimeGenerationProperty);

        $primaryBlock = new PhpBlock;
        $primaryBlock->add(new NamespaceStatement(QualifiedIdentifier::fromString(
            $className->parent()->toRelative()->string()
        )));
        $primaryBlock->add($classDefinition);

        $syntaxTree = new SyntaxTree;
        $syntaxTree->add($primaryBlock);

        return $syntaxTree;
    }

    /**
     * @return ConcreteMethod
     */
    protected function generateGetMethod()
    {
        $this->typeCheck->generateGetMethod(func_get_args());

        $staticConstant = new Constant(new Identifier('static'));
        $classNameIdentifier = new Identifier('className');
        $classNameVariable = new Variable($classNameIdentifier);
        $argumentsIdentifier = new Identifier('arguments');
        $argumentsVariable = new Variable($argumentsIdentifier);
        $staticInstances = new StaticMember(
            $staticConstant,
            new Variable(new Identifier('instances'))
        );

        $method = new ConcreteMethod(
            new Identifier('get'),
            AccessModifier::PUBLIC_(),
            true
        );
        $method->addParameter(new Parameter($classNameIdentifier));
        $argumentsParameter = new Parameter(
            $argumentsIdentifier,
            new ArrayTypeHint
        );
        $argumentsParameter->setDefaultValue(new Literal(null));
        $method->addParameter($argumentsParameter);

        $dummyModeIf = new IfStatement(
            new StaticMember(
                $staticConstant,
                new Variable(new Identifier('dummyMode'))
            )
        );
        $newDummyCall = new Call(QualifiedIdentifier::fromString('DummyValidator'));
        $newDummy = new NewOperator($newDummyCall);
        $dummyModeIf->trueBranch()->add(new ReturnStatement($newDummy));
        $method->statementBlock()->add($dummyModeIf);

        $existsCall = new Call(QualifiedIdentifier::fromString('\array_key_exists'));
        $existsCall->addMany(array(
            $classNameVariable,
            $staticInstances,
        ));
        $nonExistantIf = new IfStatement(new LogicalNot($existsCall));
        $installCall = new Call(new StaticMember(
            $staticConstant,
            new Constant(new Identifier('install'))
        ));
        $installCall->add($classNameVariable);
        $createValidatorCall = new Call(new StaticMember(
            $staticConstant,
            new Constant(new Identifier('createValidator'))
        ));
        $createValidatorCall->add($classNameVariable);
        $installCall->add($createValidatorCall);
        $nonExistantIf->trueBranch()->add(new ExpressionStatement($installCall));
        $method->statementBlock()->add($nonExistantIf);

        $validatorVariable = new Variable(new Identifier('validator'));
        $method->statementBlock()->add(new ExpressionStatement(new Assign(
            $validatorVariable,
            new Subscript(
                $staticInstances,
                $classNameVariable
            )
        )));

        $nonNullArgumentsIf = new IfStatement(new StrictNotEquals(
            new Literal(null),
            $argumentsVariable
        ));
        $validateConstructCall = new Call(new Member(
            $validatorVariable,
            new Constant(new Identifier('validateConstruct'))
        ));
        $validateConstructCall->add($argumentsVariable);
        $nonNullArgumentsIf->trueBranch()->add(new ExpressionStatement(
            $validateConstructCall
        ));
        $method->statementBlock()->add($nonNullArgumentsIf);

        $method->statementBlock()->add(new ReturnStatement($validatorVariable));

        return $method;
    }

    /**
     * @return ConcreteMethod
     */
    protected function generateInstallMethod()
    {
        $this->typeCheck->generateInstallMethod(func_get_args());

        $classNameIdentifier = new Identifier('className');
        $validatorIdentifier = new Identifier('validator');

        $method = new ConcreteMethod(
            new Identifier('install'),
            AccessModifier::PUBLIC_(),
            true
        );
        $method->addParameter(new Parameter($classNameIdentifier));
        $method->addParameter(new Parameter($validatorIdentifier));

        $method->statementBlock()->add(new ExpressionStatement(
            new Assign(
                new Subscript(
                    new StaticMember(
                        new Constant(new Identifier('static')),
                        new Variable(new Identifier('instances'))
                    ),
                    new Variable($classNameIdentifier)
                ),
                new Variable($validatorIdentifier)
            )
        ));

        return $method;
    }

    /**
     * @return ConcreteMethod
     */
    protected function generateSetRuntimeGenerationMethod()
    {
        $this->typeCheck->generateSetRuntimeGenerationMethod(func_get_args());

        $runtimeGenerationIdentifier = new Identifier('runtimeGeneration');
        $runtimeGenerationVariable = new Variable($runtimeGenerationIdentifier);

        $method = new ConcreteMethod(
            new Identifier('setRuntimeGeneration'),
            AccessModifier::PUBLIC_(),
            true
        );
        $method->addParameter(new Parameter($runtimeGenerationIdentifier));

        $method->statementBlock()->add(new ExpressionStatement(
            new Assign(
                new StaticMember(
                    new Constant(new Identifier('static')),
                    $runtimeGenerationVariable
                ),
                $runtimeGenerationVariable
            )
        ));

        return $method;
    }

    /**
     * @return ConcreteMethod
     */
    protected function generateRuntimeGenerationMethod()
    {
        $this->typeCheck->generateRuntimeGenerationMethod(func_get_args());

        $method = new ConcreteMethod(
            new Identifier('runtimeGeneration'),
            AccessModifier::PUBLIC_(),
            true
        );

        $method->statementBlock()->add(new ReturnStatement(
            new StaticMember(
                new Constant(new Identifier('static')),
                new Variable(new Identifier('runtimeGeneration'))
            )
        ));

        return $method;
    }

    /**
     * @param RuntimeConfiguration $configuration
     *
     * @return ConcreteMethod
     */
    protected function generateCreateValidatorMethod(
        RuntimeConfiguration $configuration
    ) {
        $this->typeCheck->generateCreateValidatorMethod(func_get_args());

        $classNameIdentifier = new Identifier('className');
        $classNameVariable = new Variable($classNameIdentifier);
        $validatorClassNameVariable = new Variable(new Identifier('validatorClassName'));

        $method = new ConcreteMethod(
            new Identifier('createValidator'),
            AccessModifier::PROTECTED_(),
            true
        );
        $method->addParameter(new Parameter($classNameIdentifier));

        $validatorClassNameConcatenation = new Concat(
            new Literal(sprintf(
                '%s\\',
                $configuration
                    ->validatorNamespace()
                    ->joinAtoms('Validator')
                    ->string()
            )),
            $classNameVariable
        );
        $validatorClassNameConcatenation->add(new Literal('TypeCheck'));
        $method->statementBlock()->add(new ExpressionStatement(new Assign(
            $validatorClassNameVariable,
            $validatorClassNameConcatenation
        )));

        $staticConstant = new Constant(new Identifier('static'));
        $runtimeGenerationCall = new Call(new StaticMember(
            $staticConstant,
            new Constant(new Identifier('runtimeGeneration'))
        ));
        $classExistsCall = new Call(QualifiedIdentifier::fromString('\class_exists'));
        $classExistsCall->add($validatorClassNameVariable);
        $runtimeGenerationIf = new IfStatement(new LogicalAnd(
            $runtimeGenerationCall,
            new LogicalNot($classExistsCall)
        ));
        $staticDummyModeVariable = new StaticMember(
            $staticConstant,
            new Variable(new Identifier('dummyMode'))
        );
        $runtimeGenerationIf->trueBranch()->add(
            new ExpressionStatement(new Assign(
                $staticDummyModeVariable,
                new Literal(true)
            ))
        );
        $defineValidatorCall = new Call(new StaticMember(
            $staticConstant,
            new Constant(new Identifier('defineValidator'))
        ));
        $defineValidatorCall->add($classNameVariable);
        $runtimeGenerationIf->trueBranch()->add(
            new ExpressionStatement($defineValidatorCall)
        );
        $runtimeGenerationIf->trueBranch()->add(
            new ExpressionStatement(new Assign(
                $staticDummyModeVariable,
                new Literal(false)
            ))
        );
        $method->statementBlock()->add($runtimeGenerationIf);

        $method->statementBlock()->add(new ReturnStatement(
            new NewOperator($validatorClassNameVariable)
        ));

        return $method;
    }

    /**
     * @param RuntimeConfiguration $configuration
     *
     * @return ConcreteMethod
     */
    protected function generateDefineValidatorMethod(
        RuntimeConfiguration $configuration
    ) {
        $this->typeCheck->generateDefineValidatorMethod(func_get_args());

        $classNameIdentifier = new Identifier('className');
        $classNameVariable = new Variable($classNameIdentifier);
        $classGeneratorIdentifier = new Identifier('classGenerator');
        $classGeneratorVariable = new Variable($classGeneratorIdentifier);
        $classGeneratorClassIdentifier = QualifiedIdentifier::fromString(
            '\Eloquent\Typhoon\Generator\ValidatorClassGenerator'
        );

        $method = new ConcreteMethod(
            new Identifier('defineValidator'),
            AccessModifier::PROTECTED_(),
            true
        );
        $method->addParameter(new Parameter($classNameIdentifier));
        $classGeneratorParameter = new Parameter(
            $classGeneratorIdentifier,
            new ObjectTypeHint($classGeneratorClassIdentifier)
        );
        $classGeneratorParameter->setDefaultValue(new Literal(null));
        $method->addParameter($classGeneratorParameter);

        $nullClassGeneratorIf = new IfStatement(new StrictEquals(
            new Literal(null),
            $classGeneratorVariable
        ));
        $nullClassGeneratorIf->trueBranch()->add(new ExpressionStatement(
            new Assign(
                $classGeneratorVariable,
                new NewOperator($classGeneratorClassIdentifier)
            )
        ));
        $method->statementBlock()->add($nullClassGeneratorIf);

        $evalCall = new Call(QualifiedIdentifier::fromString('eval'));
        $generateFromClassCall = new Call(new Member(
            $classGeneratorVariable,
            new Constant(new Identifier('generateFromClass'))
        ));
        $generateFromClassCall->add(new Call(new StaticMember(
            new Constant(new Identifier('static')),
            new Constant(new Identifier('configuration'))
        )));
        $newReflectorCall = new Call(
            QualifiedIdentifier::fromString('\ReflectionClass')
        );
        $newReflectorCall->add($classNameVariable);
        $newReflector = new NewOperator($newReflectorCall);
        $generateFromClassCall->add($newReflector);
        $evalCall->add(new Concat(
            new Literal('?>'),
            $generateFromClassCall
        ));
        $method->statementBlock()->add(new ExpressionStatement($evalCall));

        return $method;
    }

    /**
     * @param RuntimeConfiguration $configuration
     *
     * @return ConcreteMethod
     */
    protected function generateConfigurationMethod(
        RuntimeConfiguration $configuration
    ) {
        $this->typeCheck->generateConfigurationMethod(func_get_args());

        $method = new ConcreteMethod(
            new Identifier('configuration'),
            AccessModifier::PROTECTED_(),
            true
        );

        $method->statementBlock()->add(new ReturnStatement(
            $this->configurationGenerator()->generate($configuration)
        ));

        return $method;
    }

    private $renderer;
    private $configurationGenerator;
    private $typeCheck;
}
