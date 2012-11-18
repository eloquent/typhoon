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

use Eloquent\Typhoon\Configuration\RuntimeConfiguration;
use Icecave\Pasta\AST\Expr\ArrayLiteral;
use Icecave\Pasta\AST\Expr\Assign;
use Icecave\Pasta\AST\Expr\Call;
use Icecave\Pasta\AST\Expr\Constant;
use Icecave\Pasta\AST\Expr\Literal;
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
use Icecave\Pasta\AST\Type\ConcreteMethod;
use Icecave\Pasta\AST\Type\Property;
use Icecave\Rasta\Renderer;
use Typhoon\Typhoon;

class FacadeGenerator
{
    /**
     * @param Renderer|null $renderer
     * @param RuntimeConfigurationGenerator|null $configurationGenerator
     */
    public function __construct(
        Renderer $renderer = null,
        RuntimeConfigurationGenerator $configurationGenerator = null
    ) {
        $this->typhoon = Typhoon::get(__CLASS__, func_get_args());

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
        $this->typhoon->renderer(func_get_args());

        return $this->renderer;
    }

    /**
     * @return RuntimeConfigurationGenerator
     */
    public function configurationGenerator()
    {
        $this->typhoon->configurationGenerator(func_get_args());

        return $this->configurationGenerator;
    }

    /**
     * @param RuntimeConfiguration $configuration
     * @param string|null &$namespaceName
     * @param string|null &$className
     *
     * @return string
     */
    public function generate(
        RuntimeConfiguration $configuration,
        &$namespaceName = null,
        &$className = null
    ) {
        $this->typhoon->generate(func_get_args());

        return $this->generateSyntaxTree(
            $configuration,
            $namespaceName,
            $className
        )->accept($this->renderer());
    }

    /**
     * @param RuntimeConfiguration $configuration
     * @param string|null &$namespaceName
     * @param string|null &$className
     *
     * @return SyntaxTree
     */
    public function generateSyntaxTree(
        RuntimeConfiguration $configuration,
        &$namespaceName = null,
        &$className = null
    ) {
        $this->typhoon->generateSyntaxTree(func_get_args());

        list($namespaceName, $className) = $this->facadeClassName(
            $configuration,
            $namespaceName,
            $className
        );

        $classDefinition = new ClassDefinition(
            new Identifier($className)
        );
        $classDefinition->add($this->generateGetMethod());
        $classDefinition->add($this->generateInstallMethod());
        $classDefinition->add($this->generateSetRuntimeGenerationMethod());
        $classDefinition->add($this->generateRuntimeGenerationMethod());
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

        $classDefinition->add(new Property(
            new Identifier('configuration'),
            AccessModifier::PRIVATE_(),
            true
        ));

        $primaryBlock = new PhpBlock;
        $primaryBlock->add(new NamespaceStatement(
            QualifiedIdentifier::fromString($namespaceName)
        ));
        $primaryBlock->add($classDefinition);

        $syntaxTree = new SyntaxTree;
        $syntaxTree->add($primaryBlock);

        return $syntaxTree;
    }

    /**
     * @param RuntimeConfiguration $configuration
     * @param string|null $namespaceName
     * @param string|null $className
     *
     * @return tuple<string, string>
     */
    protected function facadeClassName(
        RuntimeConfiguration $configuration,
        $namespaceName = null,
        $className = null
    ) {
        $this->typhoon->facadeClassName(func_get_args());

        return array(
            'Typhoon',
            'Typhoon',
        );
    }

    /**
     * @return ConcreteMethod
     */
    protected function generateGetMethod()
    {
        $this->typhoon->generateGetMethod(func_get_args());

        $staticKeyword = new Constant(new Identifier('static'));
        $classNameIdentifier = new Identifier('className');
        $classNameVariable = new Variable($classNameIdentifier);
        $argumentsIdentifier = new Identifier('arguments');
        $argumentsVariable = new Variable($argumentsIdentifier);
        $staticInstances = new StaticMember(
            $staticKeyword,
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
                $staticKeyword,
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
            $staticKeyword,
            new Constant(new Identifier('install'))
        ));
        $installCall->add($classNameVariable);
        $createValidatorCall = new Call(new StaticMember(
            $staticKeyword,
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
        $this->typhoon->generateInstallMethod(func_get_args());

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
        $this->typhoon->generateSetRuntimeGenerationMethod(func_get_args());

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
        $this->typhoon->generateRuntimeGenerationMethod(func_get_args());

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
    protected function generateConfigurationMethod(
        RuntimeConfiguration $configuration
    ) {
        $this->typhoon->generateConfigurationMethod(func_get_args());

        $method = new ConcreteMethod(
            new Identifier('configuration'),
            AccessModifier::PROTECTED_(),
            true
        );

        $staticConfiguration = new StaticMember(
            new Constant(new Identifier('static')),
            new Variable(new Identifier('configuration'))
        );

        $nonExistantIf = new IfStatement(new StrictEquals(
            new Literal(null),
            $staticConfiguration
        ));
        $nonExistantIf->trueBranch()->add(new ExpressionStatement(new Assign(
            $staticConfiguration,
            $this->configurationGenerator()->generate($configuration)
        )));
        $method->statementBlock()->add($nonExistantIf);

        $method->statementBlock()->add(new ReturnStatement(
            $staticConfiguration
        ));

        return $method;
    }

    private $typhoon;
}
