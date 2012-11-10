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
use Icecave\Pasta\AST\Expr\Call;
use Icecave\Pasta\AST\Expr\NewOperator;
use Icecave\Pasta\AST\Expr\QualifiedIdentifier;
use Icecave\Pasta\AST\Func\ArrayTypeHint;
use Icecave\Pasta\AST\Func\Parameter;
use Icecave\Pasta\AST\Identifier;
use Icecave\Pasta\AST\PhpBlock;
use Icecave\Pasta\AST\Stmt\IfStatement;
use Icecave\Pasta\AST\Stmt\NamespaceStatement;
use Icecave\Pasta\AST\Stmt\ReturnStatement;
use Icecave\Pasta\AST\SyntaxTree;
use Icecave\Pasta\AST\Type\ClassDefinition;
use Icecave\Pasta\AST\Type\ConcreteMethod;
use Icecave\Rasta\Renderer;
use Typhoon\Typhoon;

class FacadeGenerator
{
    /**
     * @param Renderer|null $renderer
     * @param RuntimeConfigurationGenerator|null $configurationGenerator
     */
    public function __construct(
        Renderer $renderer = null,
        RuntimeConfigurationGenerator $configurationGenerator = null
    ) {
        $this->typhoon = Typhoon::get(__CLASS__, func_get_args());

        if (null === $renderer) {
            $renderer = new Renderer;
        }
        if (null === $configurationGenerator) {
            $configurationGenerator = new RuntimeConfigurationGenerator;
        }

        $this->renderer = $renderer;
        $this->configurationGenerator = $configurationGenerator;
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
     * @return RuntimeConfigurationGenerator
     */
    public function configurationGenerator()
    {
        $this->typhoon->configurationGenerator(func_get_args());

        return $this->configurationGenerator;
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

        list($namespaceName, $className) = $this->facadeClassName(
            $configuration,
            $namespaceName,
            $className
        );

        $classDefinition = new ClassDefinition(
            new Identifier($className)
        );
        $classDefinition->addMany(array(
            $this->generateGetMethod(),
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
     * @param string|null $namespaceName
     * @param string|null $className
     *
     * @return tuple<string, string>
     */
    protected function facadeClassName(
        RuntimeConfiguration $configuration,
        $namespaceName = null,
        $className = null
    ) {
        $this->typhoon->facadeClassName(func_get_args());

        return array(
            'Typhoon',
            'Typhoon',
        );
    }

    /**
     * @return ConcreteMethod
     */
    protected function generateGetMethod()
    {
        $method = new ConcreteMethod(new Identifier('get'), true);
        $method->addParameter(new Parameter(
            new Identifier('className')
        ));
        $argumentsParameter = new Parameter(
            new Identifier('arguments'),
            new ArrayTypeHint
        );
        $argumentsParameter->setDefaultValue(null);
        $method->addParameter($argumentsParameter);

        $newDummyCall = new Call(QualifiedIdentifier::fromString('DummyValidator'));
        $newDummy = new NewOperator($newDummyCall);
        $dummyModeIf = new IfStatement($newDummyCall);
        $dummyModeIf->trueBranch()->add(new ReturnStatement($newDummy));
        $method->statementBlock()->add($dummyModeIf);

        return $method;
    }

    private $typhoon;
}
