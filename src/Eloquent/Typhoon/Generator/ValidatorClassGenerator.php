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

use Eloquent\Typhax\Resolver\ObjectTypeClassNameResolver;
use Eloquent\Typhoon\ClassMapper\ClassDefinition;
use Eloquent\Typhoon\ClassMapper\ClassMapper;
use Eloquent\Typhoon\Configuration\RuntimeConfiguration;
use Eloquent\Typhoon\Parameter\ParameterList;
use Eloquent\Typhoon\Parser\ParameterListParser;
use Eloquent\Typhoon\Resolver\ParameterListClassNameResolver;
use Icecave\Isolator\Isolator;
use Icecave\Pasta\AST\Expr\QualifiedIdentifier;
use Icecave\Pasta\AST\Func\ArrayTypeHint;
use Icecave\Pasta\AST\Func\Parameter as ParameterASTNode;
use Icecave\Pasta\AST\Identifier;
use Icecave\Pasta\AST\PhpBlock;
use Icecave\Pasta\AST\Stmt\NamespaceStatement;
use Icecave\Pasta\AST\SyntaxTree;
use Icecave\Pasta\AST\Type\ClassDefinition as ClassDefinitionASTNode;
use Icecave\Pasta\AST\Type\ConcreteMethod;
use Icecave\Rasta\Renderer;
use ReflectionClass;
use ReflectionMethod;
use Typhoon\Typhoon;

class ValidatorClassGenerator
{
    /**
     * @param Renderer|null                     $renderer
     * @param ParameterListParser|null          $parser
     * @param ParameterListGenerator|null       $generator
     * @param ClassMapper|null                  $classMapper
     * @param NativeParameterListMergeTool|null $nativeMergeTool
     * @param Isolator|null                     $isolator
     */
    public function __construct(
        Renderer $renderer = null,
        ParameterListParser $parser = null,
        ParameterListGenerator $generator = null,
        ClassMapper $classMapper = null,
        NativeParameterListMergeTool $nativeMergeTool = null,
        Isolator $isolator = null
    ) {
        $this->typhoon = Typhoon::get(__CLASS__, func_get_args());

        if (null === $renderer) {
            $renderer = new Renderer;
        }
        if (null === $parser) {
            $parser = new ParameterListParser;
        }
        if (null === $generator) {
            $generator = new ParameterListGenerator;
        }
        if (null === $classMapper) {
            $classMapper = new ClassMapper;
        }
        if (null === $nativeMergeTool) {
            $nativeMergeTool = new NativeParameterListMergeTool;
        }

        $this->renderer = $renderer;
        $this->parser = $parser;
        $this->generator = $generator;
        $this->classMapper = $classMapper;
        $this->nativeMergeTool = $nativeMergeTool;
        $this->isolator = Isolator::get($isolator);
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
     * @return ParameterListParser
     */
    public function parser()
    {
        $this->typhoon->parser(func_get_args());

        return $this->parser;
    }

    /**
     * @return ParameterListGenerator
     */
    public function generator()
    {
        $this->typhoon->generator(func_get_args());

        return $this->generator;
    }

    /**
     * @return ClassMapper
     */
    public function classMapper()
    {
        $this->typhoon->classMapper(func_get_args());

        return $this->classMapper;
    }

    /**
     * @return NativeParameterListMergeTool
     */
    public function nativeMergeTool()
    {
        $this->typhoon->nativeMergeTool(func_get_args());

        return $this->nativeMergeTool;
    }

    /**
     * @param RuntimeConfiguration $configuration
     * @param ClassDefinition      $classDefinition
     * @param string|null &$namespaceName
     * @param string|null &$className
     *
     * @return string
     */
    public function generate(
        RuntimeConfiguration $configuration,
        ClassDefinition $classDefinition,
        &$namespaceName = null,
        &$className = null
    ) {
        $this->typhoon->generate(func_get_args());

        return $this->generateSyntaxTree(
            $configuration,
            $classDefinition,
            $namespaceName,
            $className
        )->accept($this->renderer());
    }

    /**
     * @param RuntimeConfiguration $configuration
     * @param string               $sourceClassName
     * @param string               $source
     * @param string|null &$namespaceName
     * @param string|null &$className
     *
     * @return string
     */
    public function generateFromSource(
        RuntimeConfiguration $configuration,
        $sourceClassName,
        $source,
        &$namespaceName = null,
        &$className = null
    ) {
        $this->typhoon->generateFromSource(func_get_args());

        return $this->generate(
            $configuration,
            $this->classMapper()->classBySource($sourceClassName, $source),
            $namespaceName,
            $className
        );
    }

    /**
     * @param RuntimeConfiguration $configuration
     * @param string               $sourceClassName
     * @param string               $path
     * @param string|null &$namespaceName
     * @param string|null &$className
     *
     * @return string
     */
    public function generateFromFile(
        RuntimeConfiguration $configuration,
        $sourceClassName,
        $path,
        &$namespaceName = null,
        &$className = null
    ) {
        $this->typhoon->generateFromFile(func_get_args());

        return $this->generateFromSource(
            $configuration,
            $sourceClassName,
            $this->isolator->file_get_contents(
                $path
            ),
            $namespaceName,
            $className
        );
    }

    /**
     * @param RuntimeConfiguration $configuration
     * @param ReflectionClass      $class
     * @param string|null &$namespaceName
     * @param string|null &$className
     *
     * @return string
     */
    public function generateFromClass(
        RuntimeConfiguration $configuration,
        ReflectionClass $class,
        &$namespaceName = null,
        &$className = null
    ) {
        $this->typhoon->generateFromClass(func_get_args());

        return $this->generateFromFile(
            $configuration,
            $class->getName(),
            $class->getFileName(),
            $namespaceName,
            $className
        );
    }

    /**
     * @param RuntimeConfiguration $configuration
     * @param ClassDefinition      $classDefinition
     * @param string|null &$namespaceName
     * @param string|null &$className
     *
     * @return SyntaxTree
     */
    public function generateSyntaxTree(
        RuntimeConfiguration $configuration,
        ClassDefinition $classDefinition,
        &$namespaceName = null,
        &$className = null
    ) {
        $this->typhoon->generateSyntaxTree(func_get_args());

        list($namespaceName, $className) = $this->validatorClassName(
            $configuration,
            $classDefinition,
            $namespaceName,
            $className
        );

        $classDefinitionASTNode = new ClassDefinitionASTNode(
            new Identifier($className)
        );
        $classDefinitionASTNode->setParentName(QualifiedIdentifier::fromString(
            sprintf('\%s\Validator', $configuration->validatorNamespace())
        ));
        foreach ($this->methods($classDefinition) as $method) {
            $classDefinitionASTNode->add(
                $this->generateMethod($configuration, $method, $classDefinition)
            );
        }

        $primaryBlock = new PhpBlock;
        $primaryBlock->add(new NamespaceStatement(
            QualifiedIdentifier::fromString($namespaceName)
        ));
        $primaryBlock->add($classDefinitionASTNode);

        $syntaxTree = new SyntaxTree;
        $syntaxTree->add($primaryBlock);

        return $syntaxTree;
    }

    /**
     * @param RuntimeConfiguration $configuration
     * @param ReflectionMethod     $method
     * @param ClassDefinition      $classDefinition
     *
     * @return ConcreteMethod
     */
    protected function generateMethod(
        RuntimeConfiguration $configuration,
        ReflectionMethod $method,
        ClassDefinition $classDefinition
    ) {
        $this->typhoon->generateMethod(func_get_args());

        $typhoonMethod = new ConcreteMethod(
            new Identifier(
                $this->validatorMethodName($method)
            )
        );
        $typhoonMethod->addParameter(new ParameterASTNode(
            new Identifier('arguments'),
            new ArrayTypeHint
        ));

        $expressions = $this->parameterList($configuration, $method, $classDefinition)
            ->accept($this->generator())
        ;
        foreach ($expressions as $expression) {
            $typhoonMethod->statementBlock()->add($expression);
        }

        return $typhoonMethod;
    }

    /**
     * @param ClassDefinition $classDefinition
     *
     * @return array<ReflectionMethod>
     */
    protected function methods(ClassDefinition $classDefinition)
    {
        $this->typhoon->methods(func_get_args());

        $class = new ReflectionClass(
            $classDefinition->canonicalClassName()
        );

        $methods = array();
        foreach ($class->getMethods() as $method) {
            if ($class->getName() === $method->getDeclaringClass()->getName()) {
                $methods[] = $method;
            }
        }

        return $methods;
    }

    /**
     * @param RuntimeConfiguration $configuration
     * @param ClassDefinition      $classDefinition
     * @param string|null          $namespaceName
     * @param string|null          $className
     *
     * @return tuple<string, string>
     */
    protected function validatorClassName(
        RuntimeConfiguration $configuration,
        ClassDefinition $classDefinition,
        $namespaceName = null,
        $className = null
    ) {
        $this->typhoon->validatorClassName(func_get_args());

        $namespaceNameParts = array(
            $configuration->validatorNamespace(),
        );
        if (null !== $classDefinition->namespaceName()) {
            $namespaceNameParts[] = $classDefinition->namespaceName();
        }

        return array(
            implode('\\', $namespaceNameParts),
            sprintf('%sTyphoon', $classDefinition->className()),
        );
    }

    /**
     * @param ReflectionMethod $method
     *
     * @return string
     */
    protected function validatorMethodName(ReflectionMethod $method)
    {
        $this->typhoon->validatorMethodName(func_get_args());

        $methodName = $method->getName();
        if ('__' === substr($methodName, 0, 2)) {
            $methodName = sprintf(
                'validate%s',
                ucfirst(substr($methodName, 2))
            );
        }

        return $methodName;
    }

    /**
     * @param RuntimeConfiguration $configuration
     * @param ReflectionMethod     $method
     * @param ClassDefinition      $classDefinition
     *
     * @return ParameterList
     */
    protected function parameterList(
        RuntimeConfiguration $configuration,
        ReflectionMethod $method,
        ClassDefinition $classDefinition
    ) {
        $this->typhoon->parameterList(func_get_args());

        $methodName = sprintf(
            '%s::%s',
            $method->getDeclaringClass()->getName(),
            $method->getName()
        );

        $blockComment = $method->getDocComment();
        if (false === $blockComment) {
            $parameterList = new ParameterList;
        } else {
            $parameterList = $this->parser()->parseBlockComment(
                $methodName,
                $blockComment
            );
        }

        return $this->nativeMergeTool()->merge(
            $configuration,
            $methodName,
            $parameterList
                ->accept($this->classNameResolver($classDefinition))
            ,
            $this->parser()->parseReflector($method)
        );
    }

    /**
     * @param ClassDefinition $classDefinition
     *
     * @return ParameterListClassNameResolver
     */
    protected function classNameResolver(ClassDefinition $classDefinition)
    {
        $this->typhoon->classNameResolver(func_get_args());

        return new ParameterListClassNameResolver(
            new ObjectTypeClassNameResolver(
                $classDefinition->classNameResolver()
            )
        );
    }

    private $renderer;
    private $parser;
    private $generator;
    private $classMapper;
    private $isolator;
    private $typhoon;
}
