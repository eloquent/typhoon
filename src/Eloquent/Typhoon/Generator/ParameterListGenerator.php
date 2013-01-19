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
use Eloquent\Typhax\Renderer\TypeRenderer;
use Eloquent\Typhax\Type\Type;
use Eloquent\Typhoon\Parameter\Parameter;
use Eloquent\Typhoon\Parameter\ParameterList;
use Eloquent\Typhoon\Parameter\Visitor;
use Eloquent\Typhoon\TypeCheck\TypeCheck;
use Icecave\Pasta\AST\Expr\Assign;
use Icecave\Pasta\AST\Expr\Call;
use Icecave\Pasta\AST\Expr\Greater;
use Icecave\Pasta\AST\Expr\Less;
use Icecave\Pasta\AST\Expr\Literal;
use Icecave\Pasta\AST\Expr\LogicalNot;
use Icecave\Pasta\AST\Expr\NewOperator;
use Icecave\Pasta\AST\Expr\PostfixIncrement;
use Icecave\Pasta\AST\Expr\QualifiedIdentifier;
use Icecave\Pasta\AST\Expr\Subscript;
use Icecave\Pasta\AST\Expr\Variable;
use Icecave\Pasta\AST\Func\Closure;
use Icecave\Pasta\AST\Func\Parameter as ParameterASTNode;
use Icecave\Pasta\AST\Stmt\ExpressionStatement;
use Icecave\Pasta\AST\Stmt\ForStatement;
use Icecave\Pasta\AST\Stmt\IfStatement;
use Icecave\Pasta\AST\Stmt\IStatement;
use Icecave\Pasta\AST\Stmt\StatementBlock;
use Icecave\Pasta\AST\Stmt\ThrowStatement;
use Icecave\Pasta\AST\Identifier;

class ParameterListGenerator implements Visitor
{
    /**
     * @param TyphaxASTGenerator|null $typeGenerator
     * @param TypeRenderer|null       $typeRenderer
     */
    public function __construct(
        TyphaxASTGenerator $typeGenerator = null,
        TypeRenderer $typeRenderer = null
    ) {
        $this->typeCheck = TypeCheck::get(__CLASS__, func_get_args());

        if (null === $typeGenerator) {
            $typeGenerator = new TyphaxASTGenerator;
        }
        if (null === $typeRenderer) {
            $typeRenderer = new TypeRenderer;
        }

        $this->typeGenerator = $typeGenerator;
        $this->typeRenderer = $typeRenderer;

        $this->argumentExpression = new Variable(new Identifier('argument'));
        $this->indexExpression = new Variable(new Identifier('index'));
        $this->validatorNamespace = ClassName::fromAtoms(array('Typhoon'), true);
    }

    /**
     * @return TyphaxASTGenerator
     */
    public function typeGenerator()
    {
        $this->typeCheck->typeGenerator(func_get_args());

        return $this->typeGenerator;
    }

    /**
     * @return TypeRenderer
     */
    public function typeRenderer()
    {
        $this->typeCheck->typeRenderer(func_get_args());

        return $this->typeRenderer;
    }

    /**
     * @param ClassName $validatorNamespace
     */
    public function setValidatorNamespace(ClassName $validatorNamespace)
    {
        $this->typeCheck->setValidatorNamespace(func_get_args());

        $this->validatorNamespace = $validatorNamespace->toAbsolute();
    }

    /**
     * @return ClassName
     */
    public function validatorNamespace()
    {
        $this->typeCheck->validatorNamespace(func_get_args());

        return $this->validatorNamespace;
    }

    /**
     * @param Parameter $parameter
     *
     * @return array<integer,IStatement>
     */
    public function visitParameter(Parameter $parameter)
    {
        $this->typeCheck->visitParameter(func_get_args());

        $typeExpression = $parameter->type()->accept($this->typeGenerator());
        if (null === $typeExpression) {
            return array();
        }

        $expressions = array();
        if ($typeExpression instanceof Closure) {
            $checkVariable = new Variable(new Identifier('check'));
            $expressions[] = new ExpressionStatement(new Assign(
                $checkVariable,
                $typeExpression
            ));
            $conditionExpression = new Call($checkVariable);
            $conditionExpression->add($this->argumentExpression);
        } else {
            $conditionExpression = $typeExpression;
        }

        $newExceptionCall = new Call(QualifiedIdentifier::fromString(
            $this->validatorNamespace()->joinAtoms(
                'Exception',
                'UnexpectedArgumentValueException'
            )->string()
        ));
        $newExceptionCall->add(new Literal($parameter->name()));
        $newExceptionCall->add($this->indexExpression);
        $newExceptionCall->add($this->argumentExpression);
        $newExceptionCall->add(new Literal(
            $this->renderTypeName($parameter->type())
        ));
        $ifStatement = new IfStatement(new LogicalNot($conditionExpression));
        $ifStatement->trueBranch()->add(
            new ThrowStatement(new NewOperator($newExceptionCall))
        );
        $expressions[] = $ifStatement;

        return $expressions;
    }

    /**
     * @param ParameterList $parameterList
     *
     * @return array<integer,IStatement>
     */
    public function visitParameterList(ParameterList $parameterList)
    {
        $this->typeCheck->visitParameterList(func_get_args());

        $expressions = array();
        $parameters = $parameterList->parameters();
        $parameterCount = count($parameters);
        $argumentsVariable = new Variable(new Identifier('arguments'));

        // empty parameter list
        if ($parameterCount < 1) {
            $zeroLiteral = new Literal(0);
            $countCall = new Call(QualifiedIdentifier::fromString('\count'));
            $countCall->add($argumentsVariable);
            $newExceptionCall = new Call(QualifiedIdentifier::fromString(
                $this->validatorNamespace()->joinAtoms(
                    'Exception',
                    'UnexpectedArgumentException'
                )->string()
            ));
            $newExceptionCall->add($zeroLiteral);
            $newExceptionCall->add(new Subscript(
                $argumentsVariable,
                $zeroLiteral
            ));
            $ifStatement = new IfStatement(new Greater($countCall, $zeroLiteral));
            $ifStatement->trueBranch()->add(
                new ThrowStatement(new NewOperator($newExceptionCall))
            );
            $expressions[] = $ifStatement;

            return $this->wrapExpressions($expressions);
        }

        $argumentCountVariable = new Variable(new Identifier('argumentCount'));
        $argumentCountCall = new Call(QualifiedIdentifier::fromString('\count'));
        $argumentCountCall->add($argumentsVariable);
        $expressions[] = new Assign(
            $argumentCountVariable,
            $argumentCountCall
        );

        // missing parameter checks
        $requiredParameterCount = count($parameterList->requiredParameters());
        $lastRequiredParameterIndex = $requiredParameterCount - 1;
        $missingParametersStatement = null;
        if ($requiredParameterCount > 0) {
            $missingParametersStatement = new IfStatement(
                new Less($argumentCountVariable, new Literal($requiredParameterCount))
            );
            for ($i = 0; $i < $lastRequiredParameterIndex; $i ++) {
                $newExceptionCall = new Call(QualifiedIdentifier::fromString(
                    $this->validatorNamespace()->joinAtoms(
                        'Exception',
                        'MissingArgumentException'
                    )->string()
                ));
                $newExceptionCall->add(new Literal($parameters[$i]->name()));
                $newExceptionCall->add(new Literal($i));
                $newExceptionCall->add(new Literal(
                    $this->renderTypeName($parameters[$i]->type())
                ));
                $ifStatement = new IfStatement(
                    new Less($argumentCountVariable, new Literal($i + 1))
                );
                $ifStatement->trueBranch()->add(
                    new ThrowStatement(new NewOperator($newExceptionCall))
                );
                $missingParametersStatement->trueBranch()->add($ifStatement);
            }
            $newExceptionCall = new Call(QualifiedIdentifier::fromString(
                $this->validatorNamespace()->joinAtoms(
                    'Exception',
                    'MissingArgumentException'
                )->string()
            ));
            $newExceptionCall->add(new Literal(
                $parameters[$lastRequiredParameterIndex]->name()
            ));
            $newExceptionCall->add(new Literal(
                $lastRequiredParameterIndex
            ));
            $newExceptionCall->add(new Literal(
                $this->renderTypeName($parameters[$lastRequiredParameterIndex]->type())
            ));
            $missingParametersStatement->trueBranch()->add(
                new ThrowStatement(new NewOperator($newExceptionCall))
            );
        }

        // unexpected arguments check
        if (!$parameterList->isVariableLength()) {
            $parameterCountLiteral = new Literal($parameterCount);
            $newExceptionCall = new Call(QualifiedIdentifier::fromString(
                $this->validatorNamespace()->joinAtoms(
                    'Exception',
                    'UnexpectedArgumentException'
                )->string()
            ));
            $newExceptionCall->add($parameterCountLiteral);
            $newExceptionCall->add(new Subscript(
                $argumentsVariable,
                $parameterCountLiteral
            ));
            $tooManyParametersStatement = new IfStatement(
                new Greater($argumentCountVariable, $parameterCountLiteral)
            );
            $tooManyParametersStatement->trueBranch()->add(
                new ThrowStatement(new NewOperator($newExceptionCall))
            );

            if ($missingParametersStatement) {
                $missingParametersStatement->setFalseBranch(
                    $tooManyParametersStatement
                );
            } else {
                $expressions[] = $tooManyParametersStatement;
            }
        }

        if ($missingParametersStatement) {
            $expressions[] = $missingParametersStatement;
        }

        // type checks
        foreach ($parameters as $index => $parameter) {
            $isVariableLength =
                $parameterList->isVariableLength() &&
                $index === $parameterCount - 1
            ;

            $indexLiteral = new Literal($index);
            $oldArgumentExpression = $this->argumentExpression;
            $oldIndexExpression = $this->indexExpression;

            if (!$isVariableLength) {
                $this->indexExpression = $indexLiteral;
                $this->argumentExpression = new Subscript(
                    $argumentsVariable,
                    $this->indexExpression
                );
            }

            $parameterExpressions = $parameter->accept($this);
            if (count($parameterExpressions) < 1) {
                $this->argumentExpression = $oldArgumentExpression;
                $this->indexExpression = $oldIndexExpression;

                continue;
            }
            array_unshift(
                $parameterExpressions,
                new ExpressionStatement(new Assign(
                    new Variable(new Identifier('value')),
                    $this->argumentExpression
                ))
            );
            $parameterExpressions = $this->wrapExpressions($parameterExpressions);

            // wrap variable length in loop
            if ($isVariableLength) {
                $closure = new Closure;
                $closure->addParameter(new ParameterASTNode(new Identifier('argument')));
                $closure->addParameter(new ParameterASTNode(new Identifier('index')));
                foreach ($parameterExpressions as $expression) {
                    $closure->statementBlock()->add($expression);
                }

                $checkVariable = new Variable(new Identifier('check'));
                $indexVariable = new Variable(new Identifier('index'));
                $checkCall = new Call($checkVariable);
                $checkCall->add(new Subscript($argumentsVariable, $indexVariable));
                $checkCall->add($indexVariable);
                $loopContents = new StatementBlock;
                $loopContents->add(new ExpressionStatement($checkCall));

                $parameterExpressions = array(
                    new ExpressionStatement(new Assign($checkVariable, $closure)),
                    new ForStatement(
                        new Assign($indexVariable, $indexLiteral),
                        new Less($indexVariable, $argumentCountVariable),
                        new PostfixIncrement($indexVariable),
                        $loopContents
                    ),
                );
            }

            // wrap optional in if statement
            if ($parameter->isOptional()) {
                $if = new IfStatement(new Greater($argumentCountVariable, $indexLiteral));
                foreach ($parameterExpressions as $expression) {
                    $if->trueBranch()->add($expression);
                }
                $parameterExpressions = array($if);
            }

            foreach ($parameterExpressions as $expression) {
                $expressions[] = $expression;
            }

            $this->argumentExpression = $oldArgumentExpression;
            $this->indexExpression = $oldIndexExpression;
        }

        return $this->wrapExpressions($expressions);
    }

    /**
     * @param array $expressions
     *
     * @return array<integer,IStatement>
     */
    protected function wrapExpressions(array $expressions)
    {
        $this->typeCheck->wrapExpressions(func_get_args());

        foreach ($expressions as $index => $expression) {
            if (!$expression instanceof IStatement) {
                $expressions[$index] = new ExpressionStatement($expression);
            }
        }

        return $expressions;
    }

    /**
     * @param Type $type
     *
     * @return string
     */
    protected function renderTypeName(Type $type)
    {
        $this->typeCheck->renderTypeName(func_get_args());

        if ($type instanceof NullifiedType) {
            return $type->originalType()->accept($this->typeRenderer());
        }

        return $type->accept($this->typeRenderer());
    }

    private $typeGenerator;
    private $typeRenderer;
    private $argumentExpression;
    private $indexExpression;
    private $validatorNamespace;
    private $typeCheck;
}
