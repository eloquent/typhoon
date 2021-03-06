<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Generator\ExceptionGenerator;

use Eloquent\Cosmos\ClassName;
use Eloquent\Typhoon\Configuration\RuntimeConfiguration;
use Eloquent\Typhoon\Generator\StaticClassGenerator;
use Eloquent\Typhoon\TypeCheck\TypeCheck;
use Icecave\Pasta\AST\Expr\Call;
use Icecave\Pasta\AST\Expr\Constant;
use Icecave\Pasta\AST\Expr\Literal;
use Icecave\Pasta\AST\Expr\QualifiedIdentifier;
use Icecave\Pasta\AST\Expr\StaticMember;
use Icecave\Pasta\AST\Expr\Variable;
use Icecave\Pasta\AST\Func\ObjectTypeHint;
use Icecave\Pasta\AST\Func\Parameter;
use Icecave\Pasta\AST\Identifier;
use Icecave\Pasta\AST\PhpBlock;
use Icecave\Pasta\AST\Stmt\ExpressionStatement;
use Icecave\Pasta\AST\Stmt\NamespaceStatement;
use Icecave\Pasta\AST\Stmt\StatementBlock;
use Icecave\Pasta\AST\SyntaxTree;
use Icecave\Pasta\AST\Type\AccessModifier;
use Icecave\Pasta\AST\Type\ClassDefinition;
use Icecave\Pasta\AST\Type\ClassModifier;
use Icecave\Pasta\AST\Type\ConcreteMethod;
use Icecave\Rasta\Renderer;

class UnexpectedInputExceptionGenerator implements StaticClassGenerator
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
            ->joinAtoms('Exception', 'UnexpectedInputException')
        ;

        $classDefinition = new ClassDefinition(
            new Identifier($className->shortName()->string()),
            ClassModifier::ABSTRACT_()
        );
        $classDefinition->setParentName(
            QualifiedIdentifier::fromString('\InvalidArgumentException')
        );
        $classDefinition->add($this->generateConstructor());

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

        $messageIdentifier = new Identifier('message');
        $previousIdentifier = new Identifier('previous');

        $method = new ConcreteMethod(
            new Identifier('__construct'),
            AccessModifier::PUBLIC_()
        );
        $method->addParameter(new Parameter($messageIdentifier));
        $previousParameter = new Parameter(
            $previousIdentifier,
            new ObjectTypeHint(QualifiedIdentifier::fromString('\Exception'))
        );
        $previousParameter->setDefaultValue(new Literal(null));
        $method->addParameter($previousParameter);

        $parentConstructCall = new Call(new StaticMember(
            new Constant(new Identifier('parent')),
            new Constant(new Identifier('__construct'))
        ));
        $parentConstructCall->add(new Variable($messageIdentifier));
        $parentConstructCall->add(new Literal(0));
        $parentConstructCall->add(new Variable($previousIdentifier));
        $method->statementBlock()->add(new ExpressionStatement(
            $parentConstructCall
        ));

        return $method;
    }

    private $renderer;
    private $typeCheck;
}
