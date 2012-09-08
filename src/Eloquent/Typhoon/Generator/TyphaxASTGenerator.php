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

use Eloquent\Typhax\Type\AndType;
use Eloquent\Typhax\Type\ArrayType;
use Eloquent\Typhax\Type\BooleanType;
use Eloquent\Typhax\Type\CallableType;
use Eloquent\Typhax\Type\CompositeType;
use Eloquent\Typhax\Type\FloatType;
use Eloquent\Typhax\Type\IntegerType;
use Eloquent\Typhax\Type\MixedType;
use Eloquent\Typhax\Type\NullType;
use Eloquent\Typhax\Type\NumericType;
use Eloquent\Typhax\Type\ObjectType;
use Eloquent\Typhax\Type\OrType;
use Eloquent\Typhax\Type\ResourceType;
use Eloquent\Typhax\Type\StreamType;
use Eloquent\Typhax\Type\StringType;
use Eloquent\Typhax\Type\StringableType;
use Eloquent\Typhax\Type\TraversableType;
use Eloquent\Typhax\Type\TupleType;
use Eloquent\Typhax\Type\Visitor;
use Icecave\Pasta\AST\Expr\Assign;
use Icecave\Pasta\AST\Expr\Call;
use Icecave\Pasta\AST\Expr\InstanceOfType;
use Icecave\Pasta\AST\Expr\Literal;
use Icecave\Pasta\AST\Expr\LogicalAnd;
use Icecave\Pasta\AST\Expr\LogicalNot;
use Icecave\Pasta\AST\Expr\LogicalOr;
use Icecave\Pasta\AST\Expr\Member;
use Icecave\Pasta\AST\Expr\NewOperator;
use Icecave\Pasta\AST\Expr\QualifiedIdentifier;
use Icecave\Pasta\AST\Expr\StrictEquals;
use Icecave\Pasta\AST\Expr\Subscript;
use Icecave\Pasta\AST\Expr\Variable;
use Icecave\Pasta\AST\Func\Closure;
use Icecave\Pasta\AST\Func\Parameter;
use Icecave\Pasta\AST\Identifier;
use Icecave\Pasta\AST\Stmt\ExpressionStatement;
use Icecave\Pasta\AST\Stmt\ForeachStatement;
use Icecave\Pasta\AST\Stmt\IfStatement;
use Icecave\Pasta\AST\Stmt\ReturnStatement;
use Typhoon\Typhoon;

class TyphaxASTGenerator implements Visitor
{
    /**
     * @param Identifier|null $valueIdentifier
     */
    public function __construct(
        Identifier $valueIdentifier = null
    ) {
        $this->typhoon = Typhoon::get(__CLASS__, func_get_args());

        if (null === $valueIdentifier) {
            $valueIdentifier = new Identifier('value');
        }

        $this->valueIdentifier = $valueIdentifier;
    }

    /**
     * @param AndType $type
     *
     * @return LogicalAnd|Closure|null
     */
    public function visitAndType(AndType $type)
    {
        $this->typhoon->visitAndType(func_get_args());

        $expressions = array();
        $containsClosure = false;
        foreach ($type->types() as $subType) {
            $expression = $subType->accept($this);
            if (null !== $expression) {
                $expressions[] = $expression = $subType->accept($this);
                $containsClosure |= $expression instanceof Closure;
            }
        }

        $numExpressions = count($expressions);
        if ($numExpressions < 1) {
            return null;
        }

        if (!$containsClosure) {
            $expression = null;
            foreach ($expressions as $subExpression) {
                if ($expression) {
                    $expression->add($subExpression);
                } else {
                    $expression = new LogicalAnd($subExpression);
                }
            }

            return $expression;
        }

        $closure = new Closure;
        $closure->addParameter(new Parameter(new Identifier('value')));
        $lastExpressionIndex = $numExpressions - 1;

        for ($i = 0; $i < $lastExpressionIndex; $i ++) {
            if ($expressions[$i] instanceof Closure) {
                $checkVariable = new Variable(new Identifier('check'));
                $closure->statementBlock()->add(new ExpressionStatement(new Assign(
                    $checkVariable,
                    $expressions[$i]
                )));
                $condition = new Call(
                    $checkVariable,
                    new Variable(new Identifier('value'))
                );
            } else {
                $condition = $expressions[$i];
            }

            $ifStatement = new IfStatement(
                new LogicalNot($expressions[$i])
            );
            $ifStatement->trueBranch()->add(
                new ReturnStatement(new Literal(false))
            );
            $closure->statementBlock()->add($ifStatement);
        }

        if ($expressions[$lastExpressionIndex] instanceof Closure) {
            foreach ($expressions[$lastExpressionIndex]->statementBlock()->children() as $statement) {
                $closure->statementBlock()->add($statement);
            }
        } else {
            $closure->statementBlock()->add(new ReturnStatement(
                $expressions[$lastExpressionIndex]
            ));
        }

        return $closure;
    }

    /**
     * @param ArrayType $type
     *
     * @return Call
     */
    public function visitArrayType(ArrayType $type)
    {
        $this->typhoon->visitArrayType(func_get_args());

        $call = new Call(QualifiedIdentifier::fromString('\is_array'));
        $call->add($this->valueExpression());

        return $call;
    }

    /**
     * @param BooleanType $type
     *
     * @return Call
     */
    public function visitBooleanType(BooleanType $type)
    {
        $this->typhoon->visitBooleanType(func_get_args());

        $call = new Call(QualifiedIdentifier::fromString('\is_bool'));
        $call->add($this->valueExpression());

        return $call;
    }

    /**
     * @param CallableType $type
     *
     * @return Call
     */
    public function visitCallableType(CallableType $type)
    {
        $this->typhoon->visitCallableType(func_get_args());

        $call = new Call(QualifiedIdentifier::fromString('\is_callable'));
        $call->add($this->valueExpression());

        return $call;
    }

    /**
     * @param FloatType $type
     *
     * @return Call
     */
    public function visitFloatType(FloatType $type)
    {
        $this->typhoon->visitFloatType(func_get_args());

        $call = new Call(QualifiedIdentifier::fromString('\is_float'));
        $call->add($this->valueExpression());

        return $call;
    }

    /**
     * @param IntegerType $type
     *
     * @return Call
     */
    public function visitIntegerType(IntegerType $type)
    {
        $this->typhoon->visitIntegerType(func_get_args());

        $call = new Call(QualifiedIdentifier::fromString('\is_int'));
        $call->add($this->valueExpression());

        return $call;
    }

    /**
     * @param MixedType $type
     *
     * @return null
     */
    public function visitMixedType(MixedType $type)
    {
        $this->typhoon->visitMixedType(func_get_args());

        return null;
    }

    /**
     * @param NullType $type
     *
     * @return Call
     */
    public function visitNullType(NullType $type)
    {
        $this->typhoon->visitNullType(func_get_args());

        $call = new Call(QualifiedIdentifier::fromString('\is_null'));
        $call->add($this->valueExpression());

        return $call;
    }

    /**
     * @param NumericType $type
     *
     * @return Call
     */
    public function visitNumericType(NumericType $type)
    {
        $this->typhoon->visitNumericType(func_get_args());

        $call = new Call(QualifiedIdentifier::fromString('\is_numeric'));
        $call->add($this->valueExpression());

        return $call;
    }

    /**
     * @param ObjectType $type
     *
     * @return Call|InstanceOfType
     */
    public function visitObjectType(ObjectType $type)
    {
        $this->typhoon->visitObjectType(func_get_args());

        if (null === $type->ofType()) {
            $call = new Call(QualifiedIdentifier::fromString('\is_object'));
            $call->add($this->valueExpression());

            return $call;
        }

        return new InstanceOfType(
            $this->valueExpression(),
            QualifiedIdentifier::fromString('\\' + $type->ofType())
        );
    }

    /**
     * @param OrType $type
     *
     * @return LogicalAnd|Closure|null
     */
    public function visitOrType(OrType $type)
    {
        $this->typhoon->visitOrType(func_get_args());

        $expressions = array();
        $containsClosure = false;
        foreach ($type->types() as $subType) {
            $expression = $subType->accept($this);
            if (null === $expression) {
                return null;
            }

            $expressions[] = $expression;
            $containsClosure |= $expression instanceof Closure;
        }

        $numExpressions = count($expressions);
        if ($numExpressions < 1) {
            return null;
        }

        if (!$containsClosure) {
            $expression = null;
            foreach ($expressions as $subExpression) {
                if ($expression) {
                    $expression->add($subExpression);
                } else {
                    $expression = new LogicalOr($subExpression);
                }
            }

            return $expression;
        }

        $closure = new Closure;
        $closure->addParameter(new Parameter(new Identifier('value')));
        $lastExpressionIndex = $numExpressions - 1;

        for ($i = 0; $i < $lastExpressionIndex; $i ++) {
            if ($expressions[$i] instanceof Closure) {
                $checkVariable = new Variable(new Identifier('check'));
                $closure->statementBlock()->add(new ExpressionStatement(new Assign(
                    $checkVariable,
                    $expressions[$i]
                )));
                $condition = new Call(
                    $checkVariable,
                    new Variable(new Identifier('value'))
                );
            } else {
                $condition = $expressions[$i];
            }

            $ifStatement = new IfStatement($expressions[$i]);
            $ifStatement->trueBranch()->add(
                new ReturnStatement(new Literal(true))
            );
            $closure->statementBlock()->add($ifStatement);
        }

        if ($expressions[$lastExpressionIndex] instanceof Closure) {
            foreach ($expressions[$lastExpressionIndex]->statementBlock()->children() as $statement) {
                $closure->statementBlock()->add($statement);
            }
        } else {
            $closure->statementBlock()->add(new ReturnStatement(
                $expressions[$lastExpressionIndex]
            ));
        }

        return $closure;
    }

    /**
     * @param ResourceType $type
     *
     * @return Call|LogicalAnd
     */
    public function visitResourceType(ResourceType $type)
    {
        $this->typhoon->visitResourceType(func_get_args());

        $isResourceCall = new Call(QualifiedIdentifier::fromString('\is_resource'));
        $isResourceCall->add($this->valueExpression());

        if (null === $type->ofType()) {
            return $isResourceCall;
        }

        $getResourceTypeCall = new Call(QualifiedIdentifier::fromString('\get_resource_type'));
        $getResourceTypeCall->add($this->valueExpression());

        return new LogicalAnd(
            $isResourceCall,
            new StrictEquals(
                $getResourceTypeCall,
                new Literal($type->ofType())
            )
        );
    }

    /**
     * @param StreamType $type
     *
     * @return LogicalAnd|Closure
     */
    public function visitStreamType(StreamType $type)
    {
        $this->typhoon->visitStreamType(func_get_args());

        $isResourceCall = new Call(QualifiedIdentifier::fromString('\is_resource'));
        $isResourceCall->add($this->valueExpression());
        $getResourceTypeCall = new Call(QualifiedIdentifier::fromString('\get_resource_type'));
        $getResourceTypeCall->add($this->valueExpression());
        $isStreamExpression = new LogicalAnd(
            $isResourceCall,
            new StrictEquals(
                $getResourceTypeCall,
                new Literal('stream')
            )
        );

        if (
            null === $type->readable() &&
            null === $type->writable()
        ) {
            return $isStreamExpression;
        }

        $closure = new Closure;
        $closure->addParameter(new Parameter(new Identifier('value')));

        $ifStatement = new IfStatement(new LogicalNot($isStreamExpression));
        $ifStatement->trueBranch()->add(
            new ReturnStatement(new Literal(false))
        );
        $closure->statementBlock()->add($ifStatement);

        $streamMetaDataVariable = new Variable(new Identifier('streamMetaData'));
        $streamModeExpression = new Subscript(
            $streamMetaDataVariable,
            new Literal('mode')
        );

        $streamGetMetaDataCall = new Call(
            QualifiedIdentifier::fromString('stream_get_meta_data')
        );
        $streamGetMetaDataCall->add($this->valueExpression());
        $closure->statementBlock()->add(new ExpressionStatement(new Assign(
            $streamMetaDataVariable,
            $streamGetMetaDataCall
        )));

        if (null !== $type->readable()) {
            $isReadableCall = new Call(QualifiedIdentifier::fromString('\preg_match'));
            $isReadableCall->add(new Literal('/[r+]/'));
            $isReadableCall->add($streamModeExpression);

            if (null === $type->readable()) {
                if ($type->readable()) {
                    $closure->statementBlock()->add(new ReturnStatement($isReadableCall));
                } else {
                    $closure->statementBlock()->add(new ReturnStatement(new LogicalNot($isReadableCall)));
                }
            } else {
                if ($type->readable()) {
                    $ifStatement = new IfStatement(new LogicalNot($isReadableCall));
                    $ifStatement->trueBranch()->add(
                        new ReturnStatement(new Literal(false))
                    );
                    $closure->statementBlock()->add($ifStatement);
                } else {
                    $ifStatement = new IfStatement($isReadableCall);
                    $ifStatement->trueBranch()->add(
                        new ReturnStatement(new Literal(false))
                    );
                    $closure->statementBlock()->add($ifStatement);
                }
            }
        }

        if (null !== $type->writable()) {
            $isWritableCall = new Call(QualifiedIdentifier::fromString('\preg_match'));
            $isWritableCall->add(new Literal('/[waxc+]/'));
            $isWritableCall->add($streamModeExpression);

            if (null === $type->writable()) {
                if ($type->writable()) {
                    $closure->statementBlock()->add(new ReturnStatement($isWritableCall));
                } else {
                    $closure->statementBlock()->add(new ReturnStatement(new LogicalNot($isWritableCall)));
                }
            } else {
                if ($type->writable()) {
                    $ifStatement = new IfStatement(new LogicalNot($isWritableCall));
                    $ifStatement->trueBranch()->add(
                        new ReturnStatement(new Literal(false))
                    );
                    $closure->statementBlock()->add($ifStatement);
                } else {
                    $ifStatement = new IfStatement($isWritableCall);
                    $ifStatement->trueBranch()->add(
                        new ReturnStatement(new Literal(false))
                    );
                    $closure->statementBlock()->add($ifStatement);
                }
            }
        }

        if (
            null !== $type->readable() &&
            null !== $type->writable()
        ) {
            $closure->statementBlock()->add(new ReturnStatement(new Literal(true)));
        }

        return $closure;
    }

    /**
     * @param StringType $type
     *
     * @return Call
     */
    public function visitStringType(StringType $type)
    {
        $this->typhoon->visitStringType(func_get_args());

        $call = new Call(QualifiedIdentifier::fromString('\is_string'));
        $call->add($this->valueExpression());

        return $call;
    }

    /**
     * @param StringableType $type
     *
     * @return Closure
     */
    public function visitStringableType(StringableType $type)
    {
        $this->typhoon->visitStringableType(func_get_args());

        $closure = new Closure;
        $closure->addParameter(new Parameter($this->valueIdentifier()));
        $valueVariable = new Variable($this->valueIdentifier());

        $isStringCall = new Call(QualifiedIdentifier::fromString('\is_string'));
        $isStringCall->add($valueVariable);
        $isIntCall = new Call(QualifiedIdentifier::fromString('\is_int'));
        $isIntCall->add($valueVariable);
        $isFloatCall = new Call(QualifiedIdentifier::fromString('\is_float'));
        $isFloatCall->add($valueVariable);
        $stringablePrimitiveExpression = new LogicalOr(
            $isStringCall,
            $isIntCall
        );
        $stringablePrimitiveExpression->add($isFloatCall);
        $ifStatement = new IfStatement($stringablePrimitiveExpression);
        $ifStatement->trueBranch()->add(
            new ReturnStatement(new Literal(true))
        );
        $closure->statementBlock()->add($ifStatement);

        $isObjectCall = new Call(QualifiedIdentifier::fromString('\is_object'));
        $isObjectCall->add($valueVariable);
        $ifStatement = new IfStatement(new LogicalNot($isObjectCall));
        $ifStatement->trueBranch()->add(
            new ReturnStatement(new Literal(false))
        );
        $closure->statementBlock()->add($ifStatement);

        $newReflectorCall = new Call(QualifiedIdentifier::fromString('\ReflectionObject'));
        $newReflectorCall->add($valueVariable);
        $newReflector = new NewOperator($newReflectorCall);
        $reflectorVariable = new Variable(new Identifier('reflector'));
        $closure->statementBlock()->add(new ExpressionStatement(new Assign(
            $reflectorVariable,
            $newReflector
        )));

        $hasMethodCall = new Call(
            new Member(
                $reflectorVariable,
                'hasMethod'
            )
        );
        $hasMethodCall->add(new Literal('__toString'));
        $closure->statementBlock()->add($hasMethodCall);

        return $closure;
    }

    /**
     * @param TraversableType $type
     *
     * @return Call|Closure
     */
    public function visitTraversableType(TraversableType $type)
    {
        $this->typhoon->visitTraversableType(func_get_args());

        $primaryExpression = $type->primaryType()->accept($this);

        if (
            $type->primaryType() instanceof ArrayType &&
            $type->keyType() instanceof MixedType &&
            $type->valueType() instanceof MixedType
        ) {
            return $primaryExpression;
        }

        $closure = new Closure;
        $closure->addParameter(new Parameter($this->valueIdentifier()));
        $valueVariable = new Variable($this->valueIdentifier());

        $notTraversableExpression = new LogicalNot(
            new InstanceOfType(
                $valueVariable,
                QualifiedIdentifier::fromString('\Traversable')
            )
        );
        if (!$type->primaryType() instanceof ObjectType) {
            $isArrayCall = new Call(QualifiedIdentifier::fromString('\is_array'));
            $isArrayCall->add($valueVariable);
            $notTraversableExpression = new LogicalAnd(
                new LogicalNot($isArrayCall),
                $notTraversableExpression
            );
        }
        $ifStatement = new IfStatement($notTraversableExpression);
        $ifStatement->trueBranch()->add(
            new ReturnStatement(new Literal(false))
        );
        $closure->statementBlock()->add($ifStatement);

        $keyIdentifier = new Identifier('key');
        $subValueIdentifier = new Identifier('subValue');
        $loop = new ForeachStatement(
            $valueVariable,
            new Parameter($keyIdentifier),
            new Parameter($subValueIdentifier)
        );


        $keyVariable = new Variable($keyIdentifier);
        $oldValueIdentifier = $this->valueIdentifier;
        $this->valueIdentifier = $keyIdentifier;
        $keyExpression = $type->keyType()->accept($this);
        $this->valueIdentifier = $oldValueIdentifier;
        if ($keyExpression instanceof Closure) {
            $keyCheckVariable = new Variable(new Identifier('keyCheck'));
            $closure->statementBlock()->add(new ExpressionStatement(new Assign(
                $keyCheckVariable,
                $keyExpression
            )));
            $keyExpression = new Call($keyCheckVariable);
            $keyExpression->add($keyVariable);
        }
        if (null !== $keyExpression) {
            $ifStatement = new IfStatement(new LogicalNot($keyExpression));
            $ifStatement->trueBranch()->add(
                new ReturnStatement(new Literal(false))
            );
            $loop->add($ifStatement);
        }

        $subValueVariable = new Variable($subValueIdentifier);
        $oldValueIdentifier = $this->valueIdentifier;
        $this->valueIdentifier = $subValueIdentifier;
        $valueExpression = $type->valueType()->accept($this);
        $this->valueIdentifier = $oldValueIdentifier;
        if ($valueExpression instanceof Closure) {
            $valueCheckVariable = new Variable(new Identifier('valueCheck'));
            $closure->statementBlock()->add(new ExpressionStatement(new Assign(
                $valueCheckVariable,
                $valueExpression
            )));
            $valueExpression = new Call($valueCheckVariable);
            $valueExpression->add($subValueVariable);
        }
        if (null !== $valueExpression) {
            $ifStatement = new IfStatement(new LogicalNot($valueExpression));
            $ifStatement->trueBranch()->add(
                new ReturnStatement(new Literal(false))
            );
            $loop->add($ifStatement);
        }

        $closure->statementBlock()->add($loop);
        $closure->statementBlock()->add(new ReturnStatement(new Literal(true)));

        return $closure;
    }

    /**
     * @param TupleType $type
     *
     * @return LogicalAnd|Closure
     */
    public function visitTupleType(TupleType $type)
    {
        $this->typhoon->visitTupleType(func_get_args());

        $tupleSize = count($type->types());
        $isArrayCall = new Call(QualifiedIdentifier::fromString('\is_array'));
        $isArrayCall->add($this->valueExpression());
        $arrayKeysCall = new Call(QualifiedIdentifier::fromString('\array_keys'));
        $arrayKeysCall->add($this->valueExpression());
        $rangeCall = new Call(QualifiedIdentifier::fromString('\range'));
        $rangeCall->add(new Literal(0));
        $rangeCall->add(new Literal($tupleSize - 1));
        $sequentialKeyExpression = new StrictEquals(
            $arrayKeysCall,
            $rangeCall
        );
        $expressions = array(
            $isArrayCall,
            $sequentialKeyExpression
        );

        $closures = array();
        $closureCalls = array();
        foreach ($type->types() as $index => $subType) {
            $this->valueIndex = $index;

            $expression = $subType->accept($this);
            if ($expression instanceof Closure) {
                $closures[] = $expression;
                $checkCall = new Call($checkVariable);
                $checkCall->add($this->valueExpression());
                $closureCalls[] = $checkCall;
            } else {
                $expressions[] = $expression;
            }
        }

        $tupleExpression = null;
        foreach ($expressions as $expression) {
            if ($tupleExpression) {
                $tupleExpression->add($expression);
            } else {
                $tupleExpression = new LogicalAnd($expression);
            }
        }

        $numClosures = count($closures);
        if ($numClosures < 1) {
            return $tupleExpression;
        }

        $closure = new Closure;
        $closure->addParameter(new Parameter($this->valueIdentifier()));

        $ifStatement = new IfStatement(new LogicalNot($tupleExpression));
        $ifStatement->trueBranch()->add(
            new ReturnStatement(new Literal(false))
        );
        $closure->statementBlock()->add($ifStatement);

        $checkVariable = new Variable(new Identifier('check'));
        $lastClosureIndex = $numClosures - 1;
        for ($i = 0; $i < $lastClosureIndex; $i ++) {
            $closure->statementBlock()->add(new ExpressionStatement(new Assign(
                $checkVariable,
                $closures[$i]
            )));
            $ifStatement = new IfStatement(new LogicalNot($closureCalls[$i]));
            $ifStatement->trueBranch()->add(
                new ReturnStatement(new Literal(false))
            );
            $closure->statementBlock()->add($ifStatement);
        }

        $closure->statementBlock()->add(new ExpressionStatement(new Assign(
            $checkVariable,
            $closures[$lastClosureIndex]
        )));
        $closure->statementBlock()->add(new ReturnStatement($closureCalls[$lastClosureIndex]));

        return $closure;
    }

    /**
     * @return IExpression
     */
    protected function valueExpression()
    {
        $this->typhoon->valueExpression(func_get_args());

        $valueExpression = new Variable($this->valueIdentifier);
        if (null !== $this->valueIndex) {
            $valueExpression = new Subscript(
                $valueExpression,
                new Literal($this->valueIndex)
            );
        }

        return $valueExpression;
    }

    private $valueIdentifier;
    private $valueIndex;
    private $typhoon;
}
