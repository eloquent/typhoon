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
use Eloquent\Typhax\Resolver\ObjectTypeClassNameResolver;
use Eloquent\Typhoon\ClassMapper\ClassDefinition;
use Eloquent\Typhoon\ClassMapper\ClassMapper;
use Eloquent\Typhoon\Configuration\RuntimeConfiguration;
use Eloquent\Typhoon\Parameter\ParameterList;
use Eloquent\Typhoon\Parser\ParameterListParser;
use Eloquent\Typhoon\Resolver\ParameterListClassNameResolver;
use Eloquent\Typhoon\TypeCheck\TypeCheck;
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

class ValidatorClassGenerator
{
    /**
     * @param Renderer|null                     $renderer
     * @param ParameterListParser|null          $parser
     * @param ParameterListGenerator|null       $generator
     * @param ClassMapper|null                  $classMapper
     * @param ParameterListMerge\MergeTool|null $mergeTool
     * @param Isolator|null                     $isolator
     */
    public function __construct(
        Renderer $renderer = null,
        ParameterListParser $parser = null,
        ParameterListGenerator $generator = null,
        ClassMapper $classMapper = null,
        ParameterListMerge\MergeTool $mergeTool = null,
        Isolator $isolator = null
    ) {
        $this->typeCheck = TypeCheck::get(__CLASS__, func_get_args());

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
        if (null === $mergeTool) {
            $mergeTool = new ParameterListMerge\MergeTool;
        }

        $this->renderer = $renderer;
        $this->parser = $parser;
        $this->generator = $generator;
        $this->classMapper = $classMapper;
        $this->mergeTool = $mergeTool;
        $this->isolator = Isolator::get($isolator);
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
     * @return ParameterListParser
     */
    public function parser()
    {
        $this->typeCheck->parser(func_get_args());

        return $this->parser;
    }

    /**
     * @return ParameterListGenerator
     */
    public function generator()
    {
        $this->typeCheck->generator(func_get_args());

        return $this->generator;
    }

    /**
     * @return ClassMapper
     */
    public function classMapper()
    {
        $this->typeCheck->classMapper(func_get_args());

        return $this->classMapper;
    }

    /**
     * @return ParameterListMerge\MergeTool
     */
    public function mergeTool()
    {
        $this->typeCheck->mergeTool(func_get_args());

        return $this->mergeTool;
    }

    /**
     * @param RuntimeConfiguration $configuration
     * @param ClassDefinition      $classDefinition
     * @param null                 &$className
     *
     * @return string
     */
    public function generate(
        RuntimeConfiguration $configuration,
        ClassDefinition $classDefinition,
        &$className = null
    ) {
        $this->typeCheck->generate(func_get_args());

        return $this->generateSyntaxTree(
            $configuration,
            $classDefinition,
            $className
        )->accept($this->renderer());
    }

    /**
     * @param RuntimeConfiguration $configuration
     * @param ClassName            $sourceClassName
     * @param string               $source
     * @param null                 &$className
     *
     * @return string
     */
    public function generateFromSource(
        RuntimeConfiguration $configuration,
        ClassName $sourceClassName,
        $source,
        &$className = null
    ) {
        $this->typeCheck->generateFromSource(func_get_args());

        return $this->generate(
            $configuration,
            $this->classMapper()->classBySource($sourceClassName, $source),
            $className
        );
    }

    /**
     * @param RuntimeConfiguration $configuration
     * @param ClassName            $sourceClassName
     * @param string               $path
     * @param null                 &$className
     *
     * @return string
     */
    public function generateFromFile(
        RuntimeConfiguration $configuration,
        ClassName $sourceClassName,
        $path,
        &$className = null
    ) {
        $this->typeCheck->generateFromFile(func_get_args());

        return $this->generateFromSource(
            $configuration,
            $sourceClassName,
            $this->isolator->file_get_contents(
                $path
            ),
            $className
        );
    }

    /**
     * @param RuntimeConfiguration $configuration
     * @param ReflectionClass      $class
     * @param null                 &$className
     *
     * @return string
     */
    public function generateFromClass(
        RuntimeConfiguration $configuration,
        ReflectionClass $class,
        &$className = null
    ) {
        $this->typeCheck->generateFromClass(func_get_args());

        return $this->generateFromFile(
            $configuration,
            ClassName::fromString($class->getName())->toAbsolute(),
            $class->getFileName(),
            $className
        );
    }

    /**
     * @param RuntimeConfiguration $configuration
     * @param ClassDefinition      $classDefinition
     * @param null                 &$className
     *
     * @return SyntaxTree
     */
    public function generateSyntaxTree(
        RuntimeConfiguration $configuration,
        ClassDefinition $classDefinition,
        &$className = null
    ) {
        $this->typeCheck->generateSyntaxTree(func_get_args());

        $className = $this->validatorClassName(
            $configuration,
            $classDefinition
        );

        $classDefinitionASTNode = new ClassDefinitionASTNode(
            new Identifier($className->shortName()->string())
        );
        $classDefinitionASTNode->setParentName(QualifiedIdentifier::fromString(
            $configuration
                ->validatorNamespace()
                ->joinAtoms('AbstractValidator')
                ->string()
        ));
        foreach ($this->methods($classDefinition) as $method) {
            $classDefinitionASTNode->add(
                $this->generateMethod($configuration, $method, $classDefinition)
            );
        }

        $primaryBlock = new PhpBlock;
        $primaryBlock->add(new NamespaceStatement(QualifiedIdentifier::fromString(
            $className->parent()->toRelative()->string()
        )));
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
        $this->typeCheck->generateMethod(func_get_args());

        $typhoonMethod = new ConcreteMethod(
            new Identifier(
                $this->validatorMethodName($method)
            )
        );
        $typhoonMethod->addParameter(new ParameterASTNode(
            new Identifier('arguments'),
            new ArrayTypeHint
        ));

        $this->generator()
            ->setValidatorNamespace($configuration->validatorNamespace())
        ;
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
        $this->typeCheck->methods(func_get_args());

        $class = new ReflectionClass(
            $classDefinition->className()->string()
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
     *
     * @return ClassName
     */
    protected function validatorClassName(
        RuntimeConfiguration $configuration,
        ClassDefinition $classDefinition
    ) {
        $this->typeCheck->validatorClassName(func_get_args());

        $classNameAtoms = $configuration->validatorNamespace()->atoms();
        $classNameAtoms[] = 'Validator';
        if ($classDefinition->className()->hasParent()) {
            $classNameAtoms = array_merge(
                $classNameAtoms,
                $classDefinition->className()->parent()->atoms()
            );
        }

        $classNameAtoms[] = sprintf(
            '%sTypeCheck',
            $classDefinition->className()->shortName()->string()
        );

        return ClassName::fromAtoms($classNameAtoms, true);
    }

    /**
     * @param ReflectionMethod $method
     *
     * @return string
     */
    protected function validatorMethodName(ReflectionMethod $method)
    {
        $this->typeCheck->validatorMethodName(func_get_args());

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
        $this->typeCheck->parameterList(func_get_args());

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

        return $this->mergeTool()->merge(
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
        $this->typeCheck->classNameResolver(func_get_args());

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
    private $typeCheck;
}
