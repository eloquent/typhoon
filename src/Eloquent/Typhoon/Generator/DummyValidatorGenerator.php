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
use Icecave\Pasta\AST\Expr\Assign;
use Icecave\Pasta\AST\Expr\Call;
use Icecave\Pasta\AST\Expr\Constant;
use Icecave\Pasta\AST\Expr\Literal;
use Icecave\Pasta\AST\Expr\LogicalNot;
use Icecave\Pasta\AST\Expr\Member;
use Icecave\Pasta\AST\Expr\NewOperator;
use Icecave\Pasta\AST\Expr\QualifiedIdentifier;
use Icecave\Pasta\AST\Expr\Variable;
use Icecave\Pasta\AST\Func\ArrayTypeHint;
use Icecave\Pasta\AST\Func\Parameter;
use Icecave\Pasta\AST\Identifier;
use Icecave\Pasta\AST\PhpBlock;
use Icecave\Pasta\AST\Stmt\ExpressionStatement;
use Icecave\Pasta\AST\Stmt\IfStatement;
use Icecave\Pasta\AST\Stmt\NamespaceStatement;
use Icecave\Pasta\AST\Stmt\ReturnStatement;
use Icecave\Pasta\AST\Stmt\ThrowStatement;
use Icecave\Pasta\AST\SyntaxTree;
use Icecave\Pasta\AST\Type\AccessModifier;
use Icecave\Pasta\AST\Type\ClassDefinition;
use Icecave\Pasta\AST\Type\ClassModifier;
use Icecave\Pasta\AST\Type\ConcreteMethod;
use Icecave\Pasta\AST\Type\Property;
use Icecave\Rasta\Renderer;
use Typhoon\Typhoon;

class DummyValidatorGenerator
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

        $namespaceName = 'Typhoon';
        $className = 'DummyValidator';

        $classDefinition = new ClassDefinition(
            new Identifier($className)
        );
        $classDefinition->setParentName(
            QualifiedIdentifier::fromString('Validator')
        );
        $classDefinition->add($this->generateCallMethod());

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
     * @return ConcreteMethod
     */
    protected function generateCallMethod()
    {
        $this->typhoon->generateCallMethod(func_get_args());

        $method = new ConcreteMethod(
            new Identifier('__call'),
            AccessModifier::PUBLIC_()
        );
        $method->addParameter(new Parameter(new Identifier('name')));
        $method->addParameter(new Parameter(
            new Identifier('arguments'),
            new ArrayTypeHint
        ));

        return $method;
    }

    private $renderer;
    private $typhoon;
}
