<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Generator\ExceptionGenerator;

use Eloquent\Typhoon\Configuration\RuntimeConfiguration;
use Icecave\Pasta\AST\Expr\Assign;
use Icecave\Pasta\AST\Expr\Call;
use Icecave\Pasta\AST\Expr\Constant;
use Icecave\Pasta\AST\Expr\Literal;
use Icecave\Pasta\AST\Expr\Member;
use Icecave\Pasta\AST\Expr\NewOperator;
use Icecave\Pasta\AST\Expr\QualifiedIdentifier;
use Icecave\Pasta\AST\Expr\StaticMember;
use Icecave\Pasta\AST\Expr\StrictEquals;
use Icecave\Pasta\AST\Expr\Variable;
use Icecave\Pasta\AST\Func\ObjectTypeHint;
use Icecave\Pasta\AST\Func\Parameter;
use Icecave\Pasta\AST\Identifier;
use Icecave\Pasta\AST\PhpBlock;
use Icecave\Pasta\AST\Stmt\ExpressionStatement;
use Icecave\Pasta\AST\Stmt\IfStatement;
use Icecave\Pasta\AST\Stmt\NamespaceStatement;
use Icecave\Pasta\AST\Stmt\ReturnStatement;
use Icecave\Pasta\AST\Stmt\StatementBlock;
use Icecave\Pasta\AST\SyntaxTree;
use Icecave\Pasta\AST\Type\AccessModifier;
use Icecave\Pasta\AST\Type\ClassDefinition;
use Icecave\Pasta\AST\Type\ClassModifier;
use Icecave\Pasta\AST\Type\ConcreteMethod;
use Icecave\Pasta\AST\Type\Property;
use Icecave\Rasta\Renderer;
use Typhoon\Typhoon;

class UnexpectedArgumentExceptionGenerator
{
    /**
     * @param Renderer|null $renderer
     */
    public function __construct(Renderer $renderer = null)
    {
        $this->typhoon = Typhoon::get(__CLASS__, func_get_args());

        if (null === $renderer) {
            $renderer = new Renderer;
        }

        $this->renderer = $renderer;
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

        $namespaceName = sprintf('%s\Exception', $configuration->validatorNamespace());
        $className = 'UnexpectedArgumentException';

        $classDefinition = new ClassDefinition(
            new Identifier($className),
            ClassModifier::FINAL_()
        );
        $classDefinition->setParentName(
            QualifiedIdentifier::fromString('UnexpectedInputException')
        );
        $classDefinition->add($this->generateConstructor($configuration));
        $classDefinition->add($this->generateIndexMethod());
        $classDefinition->add($this->generateValueMethod());
        $classDefinition->add($this->generateTypeInspectorMethod());
        $classDefinition->add($this->generateUnexpectedTypeMethod());

        $classDefinition->add(new Property(
            new Identifier('index'),
            AccessModifier::PRIVATE_()
        ));
        $classDefinition->add(new Property(
            new Identifier('value'),
            AccessModifier::PRIVATE_()
        ));
        $classDefinition->add(new Property(
            new Identifier('typeInspector'),
            AccessModifier::PRIVATE_()
        ));
        $classDefinition->add(new Property(
            new Identifier('unexpectedValue'),
            AccessModifier::PRIVATE_()
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
     *
     * @return ConcreteMethod
     */
    protected function generateConstructor(RuntimeConfiguration $configuration)
    {
        $this->typhoon->generateConstructor(func_get_args());

        $indexIdentifier = new Identifier('index');
        $indexVariable = new Variable($indexIdentifier);
        $valueIdentifier = new Identifier('value');
        $valueVariable = new Variable($valueIdentifier);
        $previousIdentifier = new Identifier('previous');
        $previousVariable = new Variable($previousIdentifier);
        $typeInspectorIdentifier = new Identifier('typeInspector');
        $typeInspectorVariable = new Variable($typeInspectorIdentifier);
        $thisVariable = new Variable(new Identifier('this'));
        $thisIndexMember = new Member(
            $thisVariable,
            new Constant($indexIdentifier)
        );
        $thisValueMember = new Member(
            $thisVariable,
            new Constant($valueIdentifier)
        );
        $thisTypeInspectorMember = new Member(
            $thisVariable,
            new Constant($typeInspectorIdentifier)
        );
        $thisUnexpectedTypeMember = new Member(
            $thisVariable,
            new Constant(new Identifier('unexpectedType'))
        );
        $typeInspectorClassName = QualifiedIdentifier::fromString(sprintf(
            '\%s\TypeInspector',
            $configuration->validatorNamespace()
        ));

        $method = new ConcreteMethod(
            new Identifier('__construct'),
            AccessModifier::PUBLIC_()
        );
        $method->addParameter(new Parameter($indexIdentifier));
        $method->addParameter(new Parameter($valueIdentifier));
        $previousParameter = new Parameter(
            $previousIdentifier,
            new ObjectTypeHint(QualifiedIdentifier::fromString('\Exception'))
        );
        $previousParameter->setDefaultValue(new Literal(null));
        $method->addParameter($previousParameter);
        $typeInspectorParameter = new Parameter(
            $typeInspectorIdentifier,
            new ObjectTypeHint($typeInspectorClassName)
        );
        $typeInspectorParameter->setDefaultValue(new Literal(null));
        $method->addParameter($typeInspectorParameter);

        $nullTypeInspectorIf = new IfStatement(new StrictEquals(
            new Literal(null),
            $typeInspectorVariable
        ));
        $nullTypeInspectorIf->trueBranch()->add(new ExpressionStatement(
            new Assign(
                $typeInspectorVariable,
                new NewOperator(new Call($typeInspectorClassName))
            )
        ));
        $method->statementBlock()->add($nullTypeInspectorIf);

        $method->statementBlock()->add(new ExpressionStatement(new Assign(
            $thisIndexMember,
            $indexVariable
        )));
        $method->statementBlock()->add(new ExpressionStatement(new Assign(
            $thisValueMember,
            $valueVariable
        )));
        $method->statementBlock()->add(new ExpressionStatement(new Assign(
            $thisTypeInspectorMember,
            $typeInspectorVariable
        )));

        $typeInspectorTypeCall = new Call(new Member(
            $typeInspectorVariable,
            new Constant(new Identifier('type'))
        ));
        $typeInspectorTypeCall->add($thisValueMember);
        $method->statementBlock()->add(new ExpressionStatement(new Assign(
            $thisUnexpectedTypeMember,
            $typeInspectorTypeCall
        )));

        $sprintfCall = new Call(QualifiedIdentifier::fromString('\sprintf'));
        $sprintfCall->add(
            new Literal("Unexpected argument of type '%s' at index %d.")
        );
        $sprintfCall->add($thisUnexpectedTypeMember);
        $sprintfCall->add($indexVariable);
        $parentConstructCall = new Call(new StaticMember(
            new Constant(new Identifier('parent')),
            new Constant(new Identifier('__construct'))
        ));
        $parentConstructCall->add($sprintfCall);
        $parentConstructCall->add(new Variable($previousIdentifier));
        $method->statementBlock()->add(new ExpressionStatement(
            $parentConstructCall
        ));

        return $method;
    }

    /**
     * @return ConcreteMethod
     */
    protected function generateIndexMethod()
    {
        $this->typhoon->generateIndexMethod(func_get_args());

        $indexIdentifier = new Identifier('index');
        $method = new ConcreteMethod(
            $indexIdentifier,
            AccessModifier::PUBLIC_()
        );
        $method->statementBlock()->add(new ReturnStatement(new Member(
            new Variable(new Identifier('this')),
            new Constant($indexIdentifier)
        )));

        return $method;
    }

    /**
     * @return ConcreteMethod
     */
    protected function generateValueMethod()
    {
        $this->typhoon->generateValueMethod(func_get_args());

        $valueIdentifier = new Identifier('value');
        $method = new ConcreteMethod(
            $valueIdentifier,
            AccessModifier::PUBLIC_()
        );
        $method->statementBlock()->add(new ReturnStatement(new Member(
            new Variable(new Identifier('this')),
            new Constant($valueIdentifier)
        )));

        return $method;
    }

    /**
     * @return ConcreteMethod
     */
    protected function generateTypeInspectorMethod()
    {
        $this->typhoon->generateTypeInspectorMethod(func_get_args());

        $typeInspectorIdentifier = new Identifier('typeInspector');
        $method = new ConcreteMethod(
            $typeInspectorIdentifier,
            AccessModifier::PUBLIC_()
        );
        $method->statementBlock()->add(new ReturnStatement(new Member(
            new Variable(new Identifier('this')),
            new Constant($typeInspectorIdentifier)
        )));

        return $method;
    }

    /**
     * @return ConcreteMethod
     */
    protected function generateUnexpectedTypeMethod()
    {
        $this->typhoon->generateUnexpectedTypeMethod(func_get_args());

        $unexpectedTypeIdentifier = new Identifier('unexpectedType');
        $method = new ConcreteMethod(
            $unexpectedTypeIdentifier,
            AccessModifier::PUBLIC_()
        );
        $method->statementBlock()->add(new ReturnStatement(new Member(
            new Variable(new Identifier('this')),
            new Constant($unexpectedTypeIdentifier)
        )));

        return $method;
    }

    private $renderer;
    private $typhoon;
}
