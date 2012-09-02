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

use Eloquent\Typhax\Renderer\TypeRenderer;
use Eloquent\Typhoon\Parameter\Parameter;
use Eloquent\Typhoon\Parameter\ParameterList;
use Eloquent\Typhoon\Parameter\Visitor;
use Icecave\Pasta\AST\Expr\Assign;
use Icecave\Pasta\AST\Expr\Call;
use Icecave\Pasta\AST\Expr\Greater;
use Icecave\Pasta\AST\Expr\LogicalNot;
use Icecave\Pasta\AST\Expr\NewOperator;
use Icecave\Pasta\AST\Expr\QualifiedIdentifier;
use Icecave\Pasta\AST\Expr\Variable;
use Icecave\Pasta\AST\Func\Closure;
use Icecave\Pasta\AST\Func\Subscript;
use Icecave\Pasta\AST\Stmt\IfStatement;
use Icecave\Pasta\AST\Stmt\StatementBlock;
use Icecave\Pasta\AST\Stmt\ThrowStatement;
use Icecave\Pasta\AST\Identifier;
use Typhoon\Typhoon;

class ParameterListGenerator implements Visitor
{
    /**
     * @param TyphaxASTGenerator|null $typeGenerator
     * @param TypeRenderer|null $typeRenderer
     */
    public function __construct(
        TyphaxASTGenerator $typeGenerator = null,
        TypeRenderer $typeRenderer = null
    ) {
        $this->typhoon = Typhoon::get(__CLASS__, func_get_args());

        if (null === $typeGenerator) {
            $typeGenerator = new TyphaxASTGenerator;
        }
        if (null === $typeRenderer) {
            $typeRenderer = new TypeRenderer;
        }

        $this->typeGenerator = $typeGenerator;
        $this->typeRenderer = $typeRenderer;
    }

    /**
     * @return TyphaxASTGenerator
     */
    public function typeGenerator()
    {
        $this->typhoon->typeGenerator(func_get_args());

        return $this->typeGenerator;
    }

    /**
     * @return TypeRenderer
     */
    public function typeRenderer()
    {
        $this->typhoon->typeRenderer(func_get_args());

        return $this->typeRenderer;
    }

    /**
     * @param Parameter $parameter
     *
     * @return array<integer,IfStatement|Assign>
     */
    public function visitParameter(Parameter $parameter)
    {
        $this->typhoon->visitParameter(func_get_args());

        $expressions = array();
        $typeExpression = $parameter->type()->accept($this->typeGenerator());
        $argumentVariable = new Variable(new Identifier('argument'));

        if ($typeExpression instanceof Closure) {
            $checkVariable = new Variable(new Identifier('check'));
            $expressions[] = new Assign(
                $checkVariable,
                $typeExpression
            );
            $conditionExpression = new Call($checkVariable);
            $conditionExpression->add($argumentVariable);
        } else {
            $conditionExpression = $typeExpression;
        }

        $newExceptionCall = new Call(QualifiedIdentifier::fromString(
            '\Typhoon\Exception\UnexpectedArgumentValueException'
        ));
        $newExceptionCall->add(new Literal($parameter->name()));
        $newExceptionCall->add(new Variable(new Identifier('index')));
        $newExceptionCall->add($argumentVariable);
        $newExceptionCall->add(new Literal(
            $parameter->type()->accept($this->typeRenderer())
        ));
        $expressions[] = new IfStatement(
            new LogicalNot($conditionExpression),
            new ThrowStatement(new NewOperator($newExceptionCall))
        );

        return $expressions;
    }

    /**
     * @param ParameterList $parameterList
     *
     * @return array<integer,IfStatement>
     */
    public function visitParameterList(ParameterList $parameterList)
    {
        $this->typhoon->visitParameterList(func_get_args());

        $expressions = array();
        $parameters = $parameterList->parameters();
        $parameterCount = count($parameters);
        $argumentsVariable = new Variable(new Identifier('arguments'));

        if ($parameterCount < 1) {
            $zeroLiteral = new Literal(0);
            $countCall = new Call(QualifiedIdentifier::fromString('\count'));
            $countCall->add($argumentsVariable);
            $newExceptionCall = new Call(QualifiedIdentifier::fromString(
                '\Typhoon\Exception\UnexpectedArgumentException'
            ));
            $newExceptionCall->add($zeroLiteral);
            $newExceptionCall->add(new Subscript(
                $argumentsVariable,
                $zeroLiteral
            ));
            $expressions[] = new IfStatement(
                new Greater($countCall, $zeroLiteral),
                new ThrowStatement(new NewOperator($newExceptionCall))
            );

            return $expressions;
        }

        $requiredParameterCount = count($parameterList->requiredParameters());
        if ($requiredParameterCount > 0) {

        }

        return $expressions;
    }

    private $typeGenerator;
    private $typeRenderer;
    private $typhoon;
}
