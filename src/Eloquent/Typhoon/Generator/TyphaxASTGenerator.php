<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Generator;

use Eloquent\Typhax\Type\AndType;
use Eloquent\Typhax\Type\ArrayType;
use Eloquent\Typhax\Type\BooleanType;
use Eloquent\Typhax\Type\CallableType;
use Eloquent\Typhax\Type\ExtensionType;
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
use Eloquent\Typhoon\Extension\ExtensionLoader;
use Eloquent\Typhoon\Extension\ExtensionLoaderInterface;
use Eloquent\Typhoon\TypeCheck\TypeCheck;
use Icecave\Pasta\AST\Expr\Assign;
use Icecave\Pasta\AST\Expr\Call;
use Icecave\Pasta\AST\Expr\Constant;
use Icecave\Pasta\AST\Expr\InstanceOfType;
use Icecave\Pasta\AST\Expr\Literal;
use Icecave\Pasta\AST\Expr\LogicalAnd;
use Icecave\Pasta\AST\Expr\LogicalNot;
use Icecave\Pasta\AST\Expr\LogicalOr;
use Icecave\Pasta\AST\Expr\Member;
use Icecave\Pasta\AST\Expr\NewOperator;
use Icecave\Pasta\AST\Expr\QualifiedIdentifier;
use Icecave\Pasta\AST\Expr\StrictEquals;
use Icecave\Pasta\AST\Expr\StrictNotEquals;
use Icecave\Pasta\AST\Expr\Subscript;
use Icecave\Pasta\AST\Expr\Variable;
use Icecave\Pasta\AST\Func\Closure;
use Icecave\Pasta\AST\Func\Parameter;
use Icecave\Pasta\AST\Identifier;
use Icecave\Pasta\AST\Stmt\ExpressionStatement;
use Icecave\Pasta\AST\Stmt\ForeachStatement;
use Icecave\Pasta\AST\Stmt\IfStatement;
use Icecave\Pasta\AST\Stmt\ReturnStatement;
use Icecave\Pasta\AST\Stmt\StatementBlock;

class TyphaxASTGenerator implements Visitor
{
    /**
     * @param Identifier|null               $valueIdentifier
     * @param ExtensionLoaderInterface|null $extensionLoader
     */
    public function __construct(
        Identifier $valueIdentifier = null,
        ExtensionLoaderInterface $extensionLoader = null
    ) {
        $this->typeCheck = TypeCheck::get(__CLASS__, func_get_args());

        if (null === $valueIdentifier) {
            $valueIdentifier = new Identifier('value');
        }
        if (null === $extensionLoader) {
            $extensionLoader = new ExtensionLoader;
        }

        $this->valueIdentifier = $valueIdentifier;
        $this->extensionLoader = $extensionLoader;
    }

    /**
     * @param AndType $type
     *
     * @return LogicalAnd|Closure|null
     */
    public function visitAndType(AndType $type)
    {
        $this->typeCheck->visitAndType(func_get_args());

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
                $condition = new Call($checkVariable);
                $condition->add(new Variable(new Identifier('value')));
            } else {
                $condition = $expressions[$i];
            }

            $ifStatement = new IfStatement(
                new LogicalNot($condition)
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
        $this->typeCheck->visitArrayType(func_get_args());

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
        $this->typeCheck->visitBooleanType(func_get_args());

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
        $this->typeCheck->visitCallableType(func_get_args());

        $call = new Call(QualifiedIdentifier::fromString('\is_callable'));
        $call->add($this->valueExpression());

        return $call;
    }

    /**
     * @param ExtensionType $type
     *
     * @return Call
     */
    public function visitExtensionType(ExtensionType $type)
    {
        $this->typeCheck->visitExtensionType(func_get_args());

        $extension = $this->extensionLoader->load($type->className()->string());
        $closure = $extension->generateTypeCheck($this, $type);

        $call = new Call($closure);
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
        $this->typeCheck->visitFloatType(func_get_args());

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
        $this->typeCheck->visitIntegerType(func_get_args());

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
        $this->typeCheck->visitMixedType(func_get_args());

        return null;
    }

    /**
     * @param NullType $type
     *
     * @return StrictEquals
     */
    public function visitNullType(NullType $type)
    {
        $this->typeCheck->visitNullType(func_get_args());

        return new StrictEquals(
            $this->valueExpression(),
            new Literal(null)
        );
    }

    /**
     * @param NullifiedType $type
     *
     * @return null
     */
    public function visitNullifiedType(NullifiedType $type)
    {
        $this->typeCheck->visitNullifiedType(func_get_args());

        return null;
    }

    /**
     * @param NumericType $type
     *
     * @return Call
     */
    public function visitNumericType(NumericType $type)
    {
        $this->typeCheck->visitNumericType(func_get_args());

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
        $this->typeCheck->visitObjectType(func_get_args());

        if (null === $type->ofType()) {
            $call = new Call(QualifiedIdentifier::fromString('\is_object'));
            $call->add($this->valueExpression());

            return $call;
        }

        return new InstanceOfType(
            $this->valueExpression(),
            QualifiedIdentifier::fromString($type->ofType()->string())
        );
    }

    /**
     * @param OrType $type
     *
     * @return LogicalAnd|Closure|null
     */
    public function visitOrType(OrType $type)
    {
        $this->typeCheck->visitOrType(func_get_args());

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
        $numExpressions = count($expressions);
        $lastExpressionIndex = $numExpressions - 1;

        for ($i = 0; $i < $lastExpressionIndex; $i ++) {
            if ($expressions[$i] instanceof Closure) {
                $checkVariable = new Variable(new Identifier('check'));
                $closure->statementBlock()->add(new ExpressionStatement(new Assign(
                    $checkVariable,
                    $expressions[$i]
                )));
                $condition = new Call($checkVariable);
                $condition->add(new Variable(new Identifier('value')));
            } else {
                $condition = $expressions[$i];
            }

            $ifStatement = new IfStatement($condition);
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
        $this->typeCheck->visitResourceType(func_get_args());

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
        $this->typeCheck->visitStreamType(func_get_args());

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
            $strpbrkCall = new Call(QualifiedIdentifier::fromString('\strpbrk'));
            $strpbrkCall->add($streamModeExpression);
            $strpbrkCall->add(new Literal('r+'));
            $isReadableExpression = new StrictNotEquals(
                $strpbrkCall,
                new Literal(false)
            );

            if (null === $type->writable()) {
                if ($type->readable()) {
                    $closure->statementBlock()->add(new ReturnStatement($isReadableExpression));
                } else {
                    $closure->statementBlock()->add(new ReturnStatement(new LogicalNot($isReadableExpression)));
                }
            } else {
                if ($type->readable()) {
                    $ifStatement = new IfStatement(new LogicalNot($isReadableExpression));
                    $ifStatement->trueBranch()->add(
                        new ReturnStatement(new Literal(false))
                    );
                } else {
                    $ifStatement = new IfStatement($isReadableExpression);
                    $ifStatement->trueBranch()->add(
                        new ReturnStatement(new Literal(false))
                    );
                }
                $closure->statementBlock()->add($ifStatement);
            }
        }

        if (null !== $type->writable()) {
            $strpbrkCall = new Call(QualifiedIdentifier::fromString('\strpbrk'));
            $strpbrkCall->add($streamModeExpression);
            $strpbrkCall->add(new Literal('waxc+'));
            $isWritableExpression = new StrictNotEquals(
                $strpbrkCall,
                new Literal(false)
            );

            if ($type->writable()) {
                $closure->statementBlock()->add(new ReturnStatement($isWritableExpression));
            } else {
                $closure->statementBlock()->add(new ReturnStatement(new LogicalNot($isWritableExpression)));
            }
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
        $this->typeCheck->visitStringType(func_get_args());

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
        $this->typeCheck->visitStringableType(func_get_args());

        $closure = new Closure;
        $closure->addParameter(new Parameter($this->valueIdentifier));
        $valueVariable = new Variable($this->valueIdentifier);

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
                new Constant(new Identifier('hasMethod'))
            )
        );
        $hasMethodCall->add(new Literal('__toString'));
        $closure->statementBlock()->add(new ReturnStatement($hasMethodCall));

        return $closure;
    }

    /**
     * @param TraversableType $type
     *
     * @return Call|Closure
     */
    public function visitTraversableType(TraversableType $type)
    {
        $this->typeCheck->visitTraversableType(func_get_args());

        $primaryExpression = $type->primaryType()->accept($this);

        if (
            $type->primaryType() instanceof ArrayType &&
            $type->keyType() instanceof MixedType &&
            $type->valueType() instanceof MixedType
        ) {
            return $primaryExpression;
        }

        $closure = new Closure;
        $closure->addParameter(new Parameter($this->valueIdentifier));
        $valueVariable = new Variable($this->valueIdentifier);

        $notTraversableObjectExpression = new LogicalNot(
            new InstanceOfType(
                $valueVariable,
                QualifiedIdentifier::fromString('\Traversable')
            )
        );
        $isArrayCall = new Call(QualifiedIdentifier::fromString('\is_array'));
        $isArrayCall->add($valueVariable);
        $notArrayExpression = new LogicalNot($isArrayCall);
        if ($type->primaryType() instanceof ArrayType) {
            $notTraversableExpression = $notArrayExpression;
        } elseif ($type->primaryType() instanceof ObjectType) {
            $notTraversableExpression = $notTraversableObjectExpression;
        } else {
            $notTraversableExpression = new LogicalAnd(
                $notArrayExpression,
                $notTraversableObjectExpression
            );
        }
        $ifStatement = new IfStatement($notTraversableExpression);
        $ifStatement->trueBranch()->add(
            new ReturnStatement(new Literal(false))
        );
        $closure->statementBlock()->add($ifStatement);

        $keyIdentifier = new Identifier('key');
        $subValueIdentifier = new Identifier('subValue');
        $loopStatement = new StatementBlock;

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
            $loopStatement->add($ifStatement);
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
            $loopStatement->add($ifStatement);
        }

        $closure->statementBlock()->add(new ForeachStatement(
            $valueVariable,
            new Parameter($keyIdentifier),
            new Parameter($subValueIdentifier),
            $loopStatement
        ));
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
        $this->typeCheck->visitTupleType(func_get_args());

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
        $checkVariable = new Variable(new Identifier('check'));
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
        $this->valueIndex = null;

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
        $closure->addParameter(new Parameter($this->valueIdentifier));

        $ifStatement = new IfStatement(new LogicalNot($tupleExpression));
        $ifStatement->trueBranch()->add(
            new ReturnStatement(new Literal(false))
        );
        $closure->statementBlock()->add($ifStatement);

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
        $this->typeCheck->valueExpression(func_get_args());

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
    private $extensionLoader;
    private $typeCheck;
}
