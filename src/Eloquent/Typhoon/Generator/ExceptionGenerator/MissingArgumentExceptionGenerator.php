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

use Eloquent\Cosmos\ClassName;
use Eloquent\Typhoon\Configuration\RuntimeConfiguration;
use Eloquent\Typhoon\Generator\StaticClassGenerator;
use Eloquent\Typhoon\TypeCheck\TypeCheck;
use Icecave\Pasta\AST\Expr\Assign;
use Icecave\Pasta\AST\Expr\Call;
use Icecave\Pasta\AST\Expr\Constant;
use Icecave\Pasta\AST\Expr\Literal;
use Icecave\Pasta\AST\Expr\Member;
use Icecave\Pasta\AST\Expr\QualifiedIdentifier;
use Icecave\Pasta\AST\Expr\StaticMember;
use Icecave\Pasta\AST\Expr\Variable;
use Icecave\Pasta\AST\Func\ObjectTypeHint;
use Icecave\Pasta\AST\Func\Parameter;
use Icecave\Pasta\AST\Identifier;
use Icecave\Pasta\AST\PhpBlock;
use Icecave\Pasta\AST\Stmt\ExpressionStatement;
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

class MissingArgumentExceptionGenerator implements StaticClassGenerator
{
    /**
     * @param Renderer|null $renderer
     */
    public function __construct(Renderer $renderer = null)
    {
        $this->typeCheck = TypeCheck::get(__CLASS__, func_get_args());

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
        $this->typeCheck->renderer(func_get_args());

        return $this->renderer;
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
            ->joinAtoms('Exception', 'MissingArgumentException')
        ;

        $classDefinition = new ClassDefinition(
            new Identifier($className->shortName()->string()),
            ClassModifier::FINAL_()
        );
        $classDefinition->setParentName(
            QualifiedIdentifier::fromString('UnexpectedInputException')
        );
        $classDefinition->add($this->generateConstructor());
        $classDefinition->add($this->generateParameterNameMethod());
        $classDefinition->add($this->generateIndexMethod());
        $classDefinition->add($this->generateExpectedTypeMethod());

        $classDefinition->add(new Property(
            new Identifier('parameterName'),
            AccessModifier::PRIVATE_()
        ));
        $classDefinition->add(new Property(
            new Identifier('index'),
            AccessModifier::PRIVATE_()
        ));
        $classDefinition->add(new Property(
            new Identifier('expectedType'),
            AccessModifier::PRIVATE_()
        ));

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
    protected function generateConstructor()
    {
        $this->typeCheck->generateConstructor(func_get_args());

        $parameterNameIdentifier = new Identifier('parameterName');
        $parameterNameVariable = new Variable($parameterNameIdentifier);
        $indexIdentifier = new Identifier('index');
        $indexVariable = new Variable($indexIdentifier);
        $expectedTypeIdentifier = new Identifier('expectedType');
        $expectedTypeVariable = new Variable($expectedTypeIdentifier);
        $previousIdentifier = new Identifier('previous');
        $thisVariable = new Variable(new Identifier('this'));
        $thisParameterNameMember = new Member(
            $thisVariable,
            new Constant($parameterNameIdentifier)
        );
        $thisIndexMember = new Member(
            $thisVariable,
            new Constant($indexIdentifier)
        );
        $thisExpectedTypeMember = new Member(
            $thisVariable,
            new Constant($expectedTypeIdentifier)
        );

        $method = new ConcreteMethod(
            new Identifier('__construct'),
            AccessModifier::PUBLIC_()
        );
        $method->addParameter(new Parameter($parameterNameIdentifier));
        $method->addParameter(new Parameter($indexIdentifier));
        $method->addParameter(new Parameter($expectedTypeIdentifier));
        $previousParameter = new Parameter(
            $previousIdentifier,
            new ObjectTypeHint(QualifiedIdentifier::fromString('\Exception'))
        );
        $previousParameter->setDefaultValue(new Literal(null));
        $method->addParameter($previousParameter);

        $method->statementBlock()->add(new ExpressionStatement(new Assign(
            $thisParameterNameMember,
            $parameterNameVariable
        )));
        $method->statementBlock()->add(new ExpressionStatement(new Assign(
            $thisIndexMember,
            $indexVariable
        )));
        $method->statementBlock()->add(new ExpressionStatement(new Assign(
            $thisExpectedTypeMember,
            $expectedTypeVariable
        )));

        $sprintfCall = new Call(QualifiedIdentifier::fromString('\sprintf'));
        $sprintfCall->add(
            new Literal("Missing argument for parameter '%s' at index %d. Expected '%s'.")
        );
        $sprintfCall->add($parameterNameVariable);
        $sprintfCall->add($indexVariable);
        $sprintfCall->add($expectedTypeVariable);
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
    protected function generateParameterNameMethod()
    {
        $this->typeCheck->generateParameterNameMethod(func_get_args());

        $parameterNameIdentifier = new Identifier('parameterName');
        $method = new ConcreteMethod(
            $parameterNameIdentifier,
            AccessModifier::PUBLIC_()
        );
        $method->statementBlock()->add(new ReturnStatement(new Member(
            new Variable(new Identifier('this')),
            new Constant($parameterNameIdentifier)
        )));

        return $method;
    }

    /**
     * @return ConcreteMethod
     */
    protected function generateIndexMethod()
    {
        $this->typeCheck->generateIndexMethod(func_get_args());

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
    protected function generateExpectedTypeMethod()
    {
        $this->typeCheck->generateExpectedTypeMethod(func_get_args());

        $expectedTypeIdentifier = new Identifier('expectedType');
        $method = new ConcreteMethod(
            $expectedTypeIdentifier,
            AccessModifier::PUBLIC_()
        );
        $method->statementBlock()->add(new ReturnStatement(new Member(
            new Variable(new Identifier('this')),
            new Constant($expectedTypeIdentifier)
        )));

        return $method;
    }

    private $renderer;
    private $typeCheck;
}
