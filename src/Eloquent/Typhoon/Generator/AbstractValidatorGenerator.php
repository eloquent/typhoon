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
use Eloquent\Typhoon\TypeCheck\TypeCheck;
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

class AbstractValidatorGenerator implements StaticClassGenerator
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
        $this->typeCheck->generate(func_get_args());

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
        $this->typeCheck->generateSyntaxTree(func_get_args());

        $namespaceName = $configuration->validatorNamespace();
        $className = 'AbstractValidator';

        $classDefinition = new ClassDefinition(
            new Identifier($className),
            ClassModifier::ABSTRACT_()
        );
        $classDefinition->add($this->generateConstructor());
        $classDefinition->add($this->generateCallMethod());

        $classDefinition->add(new Property(new Identifier('reflector')));

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
    protected function generateConstructor()
    {
        $this->typeCheck->generateConstructor(func_get_args());

        $method = new ConcreteMethod(
            new Identifier('__construct'),
            AccessModifier::PUBLIC_()
        );

        $thisVariable = new Variable(new Identifier('this'));
        $newReflectorCall = new Call(
            QualifiedIdentifier::fromString('\ReflectionObject')
        );
        $newReflectorCall->add($thisVariable);
        $method->statementBlock()->add(new ExpressionStatement(new Assign(
            new Member(
                $thisVariable,
                new Constant(new Identifier('reflector'))
            ),
            new NewOperator($newReflectorCall)
        )));

        return $method;
    }

    /**
     * @return ConcreteMethod
     */
    protected function generateCallMethod()
    {
        $this->typeCheck->generateCallMethod(func_get_args());

        $nameIdentifier = new Identifier('name');
        $nameVariable = new Variable($nameIdentifier);
        $argumentsIdentifier = new Identifier('arguments');
        $validatorMethodNameVariable = new Variable(
            new Identifier('validatorMethodName')
        );
        $thisVariable = new Variable(new Identifier('this'));
        $reflectorMember = new Member(
            $thisVariable,
            new Constant(new Identifier('reflector'))
        );

        $method = new ConcreteMethod(
            new Identifier('__call'),
            AccessModifier::PUBLIC_()
        );
        $method->addParameter(new Parameter($nameIdentifier));
        $method->addParameter(new Parameter(
            $argumentsIdentifier,
            new ArrayTypeHint
        ));

        $ltrimCall = new Call(QualifiedIdentifier::fromString('\ltrim'));
        $ltrimCall->add($nameVariable);
        $ltrimCall->add(new Literal('_'));
        $ucfirstCall = new Call(QualifiedIdentifier::fromString('\ucfirst'));
        $ucfirstCall->add($ltrimCall);
        $validatorMethodNameSprintfCall = new Call(
            QualifiedIdentifier::fromString('\sprintf')
        );
        $validatorMethodNameSprintfCall->add(new Literal('validate%s'));
        $validatorMethodNameSprintfCall->add($ucfirstCall);
        $method->statementBlock()->add(new ExpressionStatement(new Assign(
            $validatorMethodNameVariable,
            $validatorMethodNameSprintfCall
        )));

        $reflectorHasMethodCall = new Call(new Member(
            $reflectorMember,
            new Constant(new Identifier('hasMethod'))
        ));
        $reflectorHasMethodCall->add($validatorMethodNameVariable);
        $undefinedMethodIf = new IfStatement(
            new LogicalNot($reflectorHasMethodCall)
        );
        $exceptionMessageSprintfCall = new Call(
            QualifiedIdentifier::fromString('\sprintf')
        );
        $exceptionMessageSprintfCall->add(
            new Literal('Call to undefined method %s::%s().')
        );
        $exceptionMessageSprintfCall->add(
            new Constant(new Identifier('__CLASS__'))
        );
        $exceptionMessageSprintfCall->add($nameVariable);
        $newBadMethodCallExceptionCall = new Call(
            QualifiedIdentifier::fromString('\BadMethodCallException')
        );
        $newBadMethodCallExceptionCall->add($exceptionMessageSprintfCall);
        $undefinedMethodIf->trueBranch()->add(new ThrowStatement(
            new NewOperator($newBadMethodCallExceptionCall)
        ));
        $method->statementBlock()->add($undefinedMethodIf);

        $getMethodCall = new Call(new Member(
            $reflectorMember,
            new Constant(new Identifier('getMethod'))
        ));
        $getMethodCall->add($validatorMethodNameVariable);
        $invokeArgsCall = new Call(new Member(
            $getMethodCall,
            new Constant(new Identifier('invokeArgs'))
        ));
        $invokeArgsCall->add($thisVariable);
        $invokeArgsCall->add(new Variable($argumentsIdentifier));
        $method->statementBlock()->add(new ReturnStatement($invokeArgsCall));

        return $method;
    }

    private $renderer;
    private $typeCheck;
}
