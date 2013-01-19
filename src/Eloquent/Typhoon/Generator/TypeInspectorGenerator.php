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
use Icecave\Pasta\AST\Expr\Constant;
use Icecave\Pasta\AST\Expr\GreaterEqual;
use Icecave\Pasta\AST\Expr\InstanceOfType;
use Icecave\Pasta\AST\Expr\Less;
use Icecave\Pasta\AST\Expr\Literal;
use Icecave\Pasta\AST\Expr\Member;
use Icecave\Pasta\AST\Expr\NewOperator;
use Icecave\Pasta\AST\Expr\QualifiedIdentifier;
use Icecave\Pasta\AST\Expr\PostfixIncrement;
use Icecave\Pasta\AST\Expr\StrictEquals;
use Icecave\Pasta\AST\Expr\Subscript;
use Icecave\Pasta\AST\Expr\Variable;
use Icecave\Pasta\AST\Func\ArrayTypeHint;
use Icecave\Pasta\AST\Func\Parameter;
use Icecave\Pasta\AST\Identifier;
use Icecave\Pasta\AST\PhpBlock;
use Icecave\Pasta\AST\Stmt\BreakStatement;
use Icecave\Pasta\AST\Stmt\ExpressionStatement;
use Icecave\Pasta\AST\Stmt\ForeachStatement;
use Icecave\Pasta\AST\Stmt\IfStatement;
use Icecave\Pasta\AST\Stmt\NamespaceStatement;
use Icecave\Pasta\AST\Stmt\ReturnStatement;
use Icecave\Pasta\AST\Stmt\StatementBlock;
use Icecave\Pasta\AST\SyntaxTree;
use Icecave\Pasta\AST\Type\AccessModifier;
use Icecave\Pasta\AST\Type\ClassDefinition;
use Icecave\Pasta\AST\Type\ConcreteMethod;
use Icecave\Rasta\Renderer;

class TypeInspectorGenerator implements StaticClassGenerator
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
            ->joinAtoms('TypeInspector')
        ;

        $classDefinition = new ClassDefinition(
            new Identifier($className->shortName()->string())
        );
        $classDefinition->add($this->generateTypeMethod());
        $classDefinition->add($this->generateArrayTypeMethod());
        $classDefinition->add($this->generateObjectTypeMethod());
        $classDefinition->add($this->generateTraversableSubTypesMethod());
        $classDefinition->add($this->generateResourceTypeMethod());
        $classDefinition->add($this->generateStreamTypeMethod());

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
    protected function generateTypeMethod()
    {
        $this->typeCheck->generateTypeMethod(func_get_args());

        $valueIdentifier = new Identifier('value');
        $valueVariable = new Variable($valueIdentifier);
        $maxIterationsIdentifier = new Identifier('maxIterations');
        $maxIterationsVariable = new Variable($maxIterationsIdentifier);
        $nativeTypeVariable = new Variable(new Identifier('nativeType'));
        $thisVariable = new Variable(new Identifier('this'));

        $method = new ConcreteMethod(
            new Identifier('type'),
            AccessModifier::PUBLIC_()
        );
        $method->addParameter(new Parameter($valueIdentifier));
        $maxIterationsParameter = new Parameter($maxIterationsIdentifier);
        $maxIterationsParameter->setDefaultValue(new Literal(10));
        $method->addParameter($maxIterationsParameter);

        $gettypeCall = new Call(QualifiedIdentifier::fromString('\gettype'));
        $gettypeCall->add($valueVariable);
        $method->statementBlock()->add(new ExpressionStatement(new Assign(
            $nativeTypeVariable,
            $gettypeCall
        )));

        $arrayTypeIf = new IfStatement(new StrictEquals(
            new Literal('array'),
            $nativeTypeVariable
        ));
        $arrayTypeCall = new Call(new Member(
            $thisVariable,
            new Constant(new Identifier('arrayType'))
        ));
        $arrayTypeCall->add($valueVariable);
        $arrayTypeCall->add($maxIterationsVariable);
        $arrayTypeIf->trueBranch()->add(new ReturnStatement($arrayTypeCall));
        $method->statementBlock()->add($arrayTypeIf);

        $doubleTypeIf = new IfStatement(new StrictEquals(
            new Literal('double'),
            $nativeTypeVariable
        ));
        $doubleTypeIf->trueBranch()->add(new ReturnStatement(new Literal('float')));
        $method->statementBlock()->add($doubleTypeIf);

        $nullTypeIf = new IfStatement(new StrictEquals(
            new Literal('NULL'),
            $nativeTypeVariable
        ));
        $nullTypeIf->trueBranch()->add(new ReturnStatement(new Literal('null')));
        $method->statementBlock()->add($nullTypeIf);

        $objectTypeIf = new IfStatement(new StrictEquals(
            new Literal('object'),
            $nativeTypeVariable
        ));
        $objectTypeCall = new Call(new Member(
            $thisVariable,
            new Constant(new Identifier('objectType'))
        ));
        $objectTypeCall->add($valueVariable);
        $objectTypeCall->add($maxIterationsVariable);
        $objectTypeIf->trueBranch()->add(new ReturnStatement($objectTypeCall));
        $method->statementBlock()->add($objectTypeIf);

        $resourceTypeIf = new IfStatement(new StrictEquals(
            new Literal('resource'),
            $nativeTypeVariable
        ));
        $resourceTypeCall = new Call(new Member(
            $thisVariable,
            new Constant(new Identifier('resourceType'))
        ));
        $resourceTypeCall->add($valueVariable);
        $resourceTypeIf->trueBranch()->add(new ReturnStatement($resourceTypeCall));
        $method->statementBlock()->add($resourceTypeIf);

        $method->statementBlock()->add(new ReturnStatement($nativeTypeVariable));

        return $method;
    }

    /**
     * @return ConcreteMethod
     */
    protected function generateArrayTypeMethod()
    {
        $this->typeCheck->generateArrayTypeMethod(func_get_args());

        $valueIdentifier = new Identifier('value');
        $valueVariable = new Variable($valueIdentifier);
        $maxIterationsIdentifier = new Identifier('maxIterations');
        $maxIterationsVariable = new Variable($maxIterationsIdentifier);

        $method = new ConcreteMethod(
            new Identifier('arrayType'),
            AccessModifier::PROTECTED_()
        );
        $method->addParameter(new Parameter(
            $valueIdentifier,
            new ArrayTypeHint
        ));
        $method->addParameter(new Parameter($maxIterationsIdentifier));

        $sprintfCall = new Call(QualifiedIdentifier::fromString('\sprintf'));
        $sprintfCall->add(new Literal('array%s'));
        $traversableSubTypesCall = new Call(new Member(
            new Variable(new Identifier('this')),
            new Constant(new Identifier('traversableSubTypes'))
        ));
        $traversableSubTypesCall->add($valueVariable);
        $traversableSubTypesCall->add($maxIterationsVariable);
        $sprintfCall->add($traversableSubTypesCall);
        $method->statementBlock()->add(new ReturnStatement($sprintfCall));

        return $method;
    }

    /**
     * @return ConcreteMethod
     */
    protected function generateObjectTypeMethod()
    {
        $this->typeCheck->generateObjectTypeMethod(func_get_args());

        $valueIdentifier = new Identifier('value');
        $valueVariable = new Variable($valueIdentifier);
        $maxIterationsIdentifier = new Identifier('maxIterations');
        $maxIterationsVariable = new Variable($maxIterationsIdentifier);
        $reflectorVariable = new Variable(new Identifier('reflector'));
        $classVariable = new Variable(new Identifier('class'));
        $traversableSubTypesVariable = new Variable(new Identifier('traversableSubTypes'));
        $thisVariable = new Variable(new Identifier('this'));

        $method = new ConcreteMethod(
            new Identifier('objectType'),
            AccessModifier::PROTECTED_()
        );
        $method->addParameter(new Parameter($valueIdentifier));
        $method->addParameter(new Parameter($maxIterationsIdentifier));

        $newReflectorCall = new Call(
            QualifiedIdentifier::fromString('\ReflectionObject')
        );
        $newReflectorCall->add($valueVariable);
        $method->statementBlock()->add(new ExpressionStatement(new Assign(
            $reflectorVariable,
            new NewOperator($newReflectorCall)
        )));

        $method->statementBlock()->add(new ExpressionStatement(new Assign(
            $classVariable,
            new Call(new Member(
                $reflectorVariable,
                new Constant(new Identifier('getName'))
            ))
        )));

        $method->statementBlock()->add(new ExpressionStatement(new Assign(
            $traversableSubTypesVariable,
            new Literal('')
        )));
        $traversableIf = new IfStatement(new InstanceOfType(
            $valueVariable,
            QualifiedIdentifier::fromString('\Traversable')
        ));
        $traversableSubTypesCall = new Call(new Member(
            $thisVariable,
            new Constant(new Identifier('traversableSubTypes'))
        ));
        $traversableSubTypesCall->add($valueVariable);
        $traversableSubTypesCall->add($maxIterationsVariable);
        $traversableIf->trueBranch()->add(new ExpressionStatement(new Assign(
            $traversableSubTypesVariable,
            $traversableSubTypesCall
        )));
        $method->statementBlock()->add($traversableIf);

        $sprintfCall = new Call(QualifiedIdentifier::fromString('\sprintf'));
        $sprintfCall->add(new Literal('%s%s'));
        $sprintfCall->add($classVariable);
        $sprintfCall->add($traversableSubTypesVariable);
        $method->statementBlock()->add(new ReturnStatement($sprintfCall));

        return $method;
    }

    /**
     * @return ConcreteMethod
     */
    protected function generateTraversableSubTypesMethod()
    {
        $this->typeCheck->generateTraversableSubTypesMethod(func_get_args());

        $valueIdentifier = new Identifier('value');
        $valueVariable = new Variable($valueIdentifier);
        $maxIterationsIdentifier = new Identifier('maxIterations');
        $maxIterationsVariable = new Variable($maxIterationsIdentifier);
        $keyTypesVariable = new Variable(new Identifier('keyTypes'));
        $valueTypesVariable = new Variable(new Identifier('valueTypes'));
        $iVariable = new Variable(new Identifier('i'));
        $keyIdentifier = new Identifier('key');
        $keyVariable = new Variable($keyIdentifier);
        $subValueIdentifier = new Identifier('subValue');
        $subValueVariable = new Variable($subValueIdentifier);
        $thisVariable = new Variable(new Identifier('this'));
        $thisTypeMember = new Member(
            $thisVariable,
            new Constant(new Identifier('type'))
        );
        $sortStringConstant = new Constant(new Identifier('SORT_STRING'));

        $method = new ConcreteMethod(
            new Identifier('traversableSubTypes'),
            AccessModifier::PROTECTED_()
        );
        $method->addParameter(new Parameter($valueIdentifier));
        $method->addParameter(new Parameter($maxIterationsIdentifier));

        $method->statementBlock()->add(new ExpressionStatement(new Assign(
            $keyTypesVariable,
            new ArrayLiteral
        )));
        $method->statementBlock()->add(new ExpressionStatement(new Assign(
            $valueTypesVariable,
            new ArrayLiteral
        )));
        $method->statementBlock()->add(new ExpressionStatement(new Assign(
            $iVariable,
            new Literal(0)
        )));

        $loop = new ForeachStatement(
            $valueVariable,
            new Parameter($keyIdentifier),
            new Parameter($subValueIdentifier)
        );

        $keyTypeArrayPushCall = new Call(
            QualifiedIdentifier::fromString('\array_push')
        );
        $keyTypeArrayPushCall->add($keyTypesVariable);
        $keyTypeCall = new Call($thisTypeMember);
        $keyTypeCall->add($keyVariable);
        $keyTypeArrayPushCall->add($keyTypeCall);
        $loop->statement()->add(new ExpressionStatement($keyTypeArrayPushCall));

        $valueTypeArrayPushCall = new Call(
            QualifiedIdentifier::fromString('\array_push')
        );
        $valueTypeArrayPushCall->add($valueTypesVariable);
        $valueTypeCall = new Call($thisTypeMember);
        $valueTypeCall->add($subValueVariable);
        $valueTypeArrayPushCall->add($valueTypeCall);
        $loop->statement()->add(new ExpressionStatement($valueTypeArrayPushCall));

        $loop->statement()->add(new ExpressionStatement(
            new PostfixIncrement($iVariable)
        ));
        $maxIterationsIf = new IfStatement(new GreaterEqual(
            $iVariable,
            $maxIterationsVariable
        ));
        $maxIterationsIf->trueBranch()->add(new BreakStatement);
        $loop->statement()->add($maxIterationsIf);

        $method->statementBlock()->add($loop);

        $countValueTypesCall = new Call(
            QualifiedIdentifier::fromString('\count')
        );
        $countValueTypesCall->add($valueTypesVariable);
        $noValuesIf = new IfStatement(new Less(
            $countValueTypesCall,
            new Literal(1)
        ));
        $noValuesIf->trueBranch()->add(new ReturnStatement(new Literal('')));
        $method->statementBlock()->add($noValuesIf);

        $keyTypesUniqueCall = new Call(
            QualifiedIdentifier::fromString('\array_unique')
        );
        $keyTypesUniqueCall->add($keyTypesVariable);
        $method->statementBlock()->add(new ExpressionStatement(new Assign(
            $keyTypesVariable,
            $keyTypesUniqueCall
        )));
        $keyTypesSortCall = new Call(
            QualifiedIdentifier::fromString('\sort')
        );
        $keyTypesSortCall->add($keyTypesVariable);
        $keyTypesSortCall->add($sortStringConstant);
        $method->statementBlock()->add(new ExpressionStatement(
            $keyTypesSortCall
        ));

        $valueTypesUniqueCall = new Call(
            QualifiedIdentifier::fromString('\array_unique')
        );
        $valueTypesUniqueCall->add($valueTypesVariable);
        $method->statementBlock()->add(new ExpressionStatement(new Assign(
            $valueTypesVariable,
            $valueTypesUniqueCall
        )));
        $valueTypesSortCall = new Call(
            QualifiedIdentifier::fromString('\sort')
        );
        $valueTypesSortCall->add($valueTypesVariable);
        $valueTypesSortCall->add($sortStringConstant);
        $method->statementBlock()->add(new ExpressionStatement(
            $valueTypesSortCall
        ));

        $keyTypesImplodeCall = new Call(
            QualifiedIdentifier::fromString('\implode')
        );
        $keyTypesImplodeCall->add(new Literal('|'));
        $keyTypesImplodeCall->add($keyTypesVariable);
        $valueTypesImplodeCall = new Call(
            QualifiedIdentifier::fromString('\implode')
        );
        $valueTypesImplodeCall->add(new Literal('|'));
        $valueTypesImplodeCall->add($valueTypesVariable);
        $sprintfCall = new Call(
            QualifiedIdentifier::fromString('\sprintf')
        );
        $sprintfCall->add(new Literal('<%s, %s>'));
        $sprintfCall->add($keyTypesImplodeCall);
        $sprintfCall->add($valueTypesImplodeCall);
        $method->statementBlock()->add(new ReturnStatement(
            $sprintfCall
        ));

        return $method;
    }

    /**
     * @return ConcreteMethod
     */
    protected function generateResourceTypeMethod()
    {
        $this->typeCheck->generateResourceTypeMethod(func_get_args());

        $valueIdentifier = new Identifier('value');
        $valueVariable = new Variable($valueIdentifier);
        $ofTypeVariable = new Variable(new Identifier('ofType'));

        $method = new ConcreteMethod(
            new Identifier('resourceType'),
            AccessModifier::PROTECTED_()
        );
        $method->addParameter(new Parameter($valueIdentifier));

        $getResourceTypeCall = new Call(
            QualifiedIdentifier::fromString('\get_resource_type')
        );
        $getResourceTypeCall->add($valueVariable);
        $method->statementBlock()->add(new ExpressionStatement(new Assign(
            $ofTypeVariable,
            $getResourceTypeCall
        )));

        $ofTypeStreamIf = new IfStatement(new StrictEquals(
            new Literal('stream'),
            $ofTypeVariable
        ));
        $streamTypeCall = new Call(new Member(
            new Variable(new Identifier('this')),
            new Constant(new Identifier('streamType'))
        ));
        $streamTypeCall->add($valueVariable);
        $ofTypeStreamIf->trueBranch()->add(new ReturnStatement(
            $streamTypeCall
        ));
        $method->statementBlock()->add($ofTypeStreamIf);

        $sprintfCall = new Call(
            QualifiedIdentifier::fromString('\sprintf')
        );
        $sprintfCall->add(new Literal('resource {ofType: %s}'));
        $sprintfCall->add($ofTypeVariable);
        $method->statementBlock()->add(new ReturnStatement(
            $sprintfCall
        ));

        return $method;
    }

    /**
     * @return ConcreteMethod
     */
    protected function generateStreamTypeMethod()
    {
        $this->typeCheck->generateStreamTypeMethod(func_get_args());

        $valueIdentifier = new Identifier('value');
        $valueVariable = new Variable($valueIdentifier);
        $metaDataVariable = new Variable(new Identifier('metaData'));
        $metaDataModeSubscript = new Subscript(
            $metaDataVariable,
            new Literal('mode')
        );
        $readableVariable = new Variable(new Identifier('readable'));
        $writableVariable = new Variable(new Identifier('writable'));

        $method = new ConcreteMethod(
            new Identifier('streamType'),
            AccessModifier::PROTECTED_()
        );
        $method->addParameter(new Parameter($valueIdentifier));

        $streamGetMetaDataCall = new Call(
            QualifiedIdentifier::fromString('\stream_get_meta_data')
        );
        $streamGetMetaDataCall->add($valueVariable);
        $method->statementBlock()->add(new ExpressionStatement(new Assign(
            $metaDataVariable,
            $streamGetMetaDataCall
        )));

        $readablePregMatchCall = new Call(
            QualifiedIdentifier::fromString('\preg_match')
        );
        $readablePregMatchCall->add(new Literal('/[r+]/'));
        $readablePregMatchCall->add($metaDataModeSubscript);
        $readableIf = new IfStatement(
            $readablePregMatchCall,
            new StatementBlock,
            new StatementBlock
        );
        $readableIf->trueBranch()->add(new ExpressionStatement(new Assign(
            $readableVariable,
            new Literal('true')
        )));
        $readableIf->falseBranch()->add(new ExpressionStatement(new Assign(
            $readableVariable,
            new Literal('false')
        )));
        $method->statementBlock()->add($readableIf);

        $writablePregMatchCall = new Call(
            QualifiedIdentifier::fromString('\preg_match')
        );
        $writablePregMatchCall->add(new Literal('/[waxc+]/'));
        $writablePregMatchCall->add($metaDataModeSubscript);
        $writableIf = new IfStatement(
            $writablePregMatchCall,
            new StatementBlock,
            new StatementBlock
        );
        $writableIf->trueBranch()->add(new ExpressionStatement(new Assign(
            $writableVariable,
            new Literal('true')
        )));
        $writableIf->falseBranch()->add(new ExpressionStatement(new Assign(
            $writableVariable,
            new Literal('false')
        )));
        $method->statementBlock()->add($writableIf);

        $sprintfCall = new Call(
            QualifiedIdentifier::fromString('\sprintf')
        );
        $sprintfCall->add(new Literal('stream {readable: %s, writable: %s}'));
        $sprintfCall->add($readableVariable);
        $sprintfCall->add($writableVariable);
        $method->statementBlock()->add(new ReturnStatement(
            $sprintfCall
        ));

        return $method;
    }

    private $renderer;
    private $typeCheck;
}
