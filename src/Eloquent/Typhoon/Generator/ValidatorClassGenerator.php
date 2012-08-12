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
use Eloquent\Typhoon\Compiler\ParameterListCompiler;
use Eloquent\Typhoon\Parameter\ParameterList;
use Eloquent\Typhoon\Parser\ParameterListParser;
use Eloquent\Typhoon\Resolver\ParameterListClassNameResolver;
use Eloquent\Typhoon\Resolver\ParameterListReflectionResolver;
use ReflectionClass;
use ReflectionMethod;

class ValidatorClassGenerator
{
    /**
     * @param string|null $namespaceName
     * @param ParameterListParser|null $parser
     * @param ParameterListCompiler|null $compiler
     */
    public function __construct(
        $namespaceName = null,
        ParameterListParser $parser = null,
        ParameterListCompiler $compiler = null
    ) {
        if (null === $namespaceName) {
            $namespaceName = 'Typhoon';
        }
        if (null === $parser) {
            $parser = new ParameterListParser;
        }
        if (null === $compiler) {
            $compiler = new ParameterListCompiler;
        }

        $this->namespaceName = $namespaceName;
        $this->parser = $parser;
        $this->compiler = $compiler;
    }

    /**
     * @return string
     */
    public function namespaceName()
    {
        return $this->namespaceName;
    }

    /**
     * @return ParameterListParser
     */
    public function parser()
    {
        return $this->parser;
    }

    /**
     * @return ParameterListCompiler
     */
    public function compiler()
    {
        return $this->compiler;
    }

    /**
     * @param ClassDefinition $classDefinition
     *
     * @return string
     */
    public function generate(ClassDefinition $classDefinition)
    {
        $namespaceName =
            $this->namespaceName().'\\'.
            $classDefinition->namespaceName()
        ;
        $className =
            $classDefinition->className().
            'Typhoon'
        ;

        $methods = '';
        foreach ($this->methods($classDefinition) as $method) {
            if ('' !== $methods) {
                $methods .= "\n";
            }

            $methods .= $this->generateMethod(
                $method,
                $classDefinition
            );
        }

        return <<<EOD
<?php
namespace $namespaceName;

class $className
{{$methods}
}

EOD;
    }

    /**
     * @param ClassDefinition $classDefinition
     *
     * @return array<ReflectionMethod>
     */
    protected function methods(ClassDefinition $classDefinition)
    {
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
     * @param ReflectionMethod $method
     * @param ClassDefinition $classDefinition
     */
    protected function generateMethod(
        ReflectionMethod $method,
        ClassDefinition $classDefinition
    ) {
        $methodName = $method->getName();
        $content = $this->indent(
            $this->parameterList($method, $classDefinition)
                ->accept($this->compiler())
            ,
            2
        );

        return <<<EOD

    public function $methodName(array \$arguments)
    {
$content
    }
EOD;
    }

    /**
     * @param ReflectionMethod $method
     * @param ClassDefinition $classDefinition
     */
    protected function parameterList(
        ReflectionMethod $method,
        ClassDefinition $classDefinition
    ) {
        $blockComment = $method->getDocComment();
        if (null === $blockComment) {
            $parameterList = ParameterList::createUnrestricted();
        } else {
            $parameterList = $this->parser()->parseBlockComment($blockComment);
        }

        return $parameterList
            ->accept($this->classNameResolver($classDefinition))
            ->accept($this->reflectionResolver($method))
        ;
    }

    /**
     * @param ClassDefinition $classDefinition
     *
     * @return ParameterListClassNameResolver
     */
    protected function classNameResolver(ClassDefinition $classDefinition) {
        return new ParameterListClassNameResolver(
            new ObjectTypeClassNameResolver(
                $classDefinition->classNameResolver()
            )
        );
    }

    /**
     * @param ClassDefinition $classDefinition
     *
     * @return ParameterListClassNameResolver
     */
    protected function reflectionResolver(ReflectionMethod $method) {
        return new ParameterListReflectionResolver($method);
    }

    /**
     * @param string $content
     * @param integer $depth
     *
     * @return string
     */
    protected function indent($content, $depth = 1) {
        $indent = str_repeat('    ', $depth);
        $lines = explode("\n", $content);
        $lines = array_map(function($line) use($indent) {
            if (!$line) {
                return '';
            }

            return $indent.$line;
        }, $lines);

        return implode("\n", $lines);
    }

    private $namespaceName;
    private $parser;
    private $compiler;
}