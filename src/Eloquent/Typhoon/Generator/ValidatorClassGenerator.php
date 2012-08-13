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
use Eloquent\Typhoon\Compiler\ParameterListCompiler;
use Eloquent\Typhoon\Parameter\ParameterList;
use Eloquent\Typhoon\Parser\ParameterListParser;
use Eloquent\Typhoon\Resolver\ParameterListClassNameResolver;
use Eloquent\Typhoon\Resolver\ParameterListReflectionResolver;
use Icecave\Isolator\Isolator;
use ReflectionClass;
use ReflectionMethod;
use Typhoon\Typhoon;

class ValidatorClassGenerator
{
    /**
     * @param ParameterListParser|null $parser
     * @param ParameterListCompiler|null $compiler
     * @param ClassMapper|null $classMapper
     * @param Isolator|null $isolator
     */
    public function __construct(
        ParameterListParser $parser = null,
        ParameterListCompiler $compiler = null,
        ClassMapper $classMapper = null,
        Isolator $isolator = null
    ) {
        $this->typhoon = Typhoon::get(__CLASS__, func_get_args());
        if (null === $parser) {
            $parser = new ParameterListParser;
        }
        if (null === $compiler) {
            $compiler = new ParameterListCompiler;
        }
        if (null === $classMapper) {
            $classMapper = new ClassMapper;
        }

        $this->parser = $parser;
        $this->compiler = $compiler;
        $this->classMapper = $classMapper;
        $this->isolator = Isolator::get($isolator);
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
     * @return ParameterListCompiler
     */
    public function compiler()
    {
        $this->typhoon->compiler(func_get_args());

        return $this->compiler;
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
     * @param ClassDefinition $classDefinition
     * @param string|null &$namespaceName
     * @param string|null &$className
     *
     * @return string
     */
    public function generate(
        ClassDefinition $classDefinition,
        &$namespaceName = null,
        &$className = null
    ) {
        $this->typhoon->generate(func_get_args());

        $namespaceName =
            'Typhoon\\'.
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

/*
 * This file was generated by [Typhoon](https://github.com/eloquent/typhoon).
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the
 * [LICENSE](https://raw.github.com/eloquent/typhoon/master/LICENSE)
 * file that is distributed with Typhoon.
 */

namespace $namespaceName;

class $className
{{$methods}
}

EOD;
    }

    /**
     * @param string $sourceClassName
     * @param string $source
     * @param string|null &$namespaceName
     * @param string|null &$className
     */
    public function generateFromSource(
        $sourceClassName,
        $source,
        &$namespaceName = null,
        &$className = null
    ) {
        $this->typhoon->generateFromSource(func_get_args());

        return $this->generate(
            $this->classMapper()->classBySource($sourceClassName, $source),
            $namespaceName,
            $className
        );
    }

    /**
     * @param string $sourceClassName
     * @param string $path
     * @param string|null &$namespaceName
     * @param string|null &$className
     */
    public function generateFromFile(
        $sourceClassName,
        $path,
        &$namespaceName = null,
        &$className = null
    ) {
        $this->typhoon->generateFromFile(func_get_args());

        return $this->generateFromSource(
            $sourceClassName,
            $this->isolator->file_get_contents(
                $path
            ),
            $namespaceName,
            $className
        );
    }

    /**
     * @param ReflectionClass $class
     * @param string|null &$namespaceName
     * @param string|null &$className
     */
    public function generateFromClass(
        ReflectionClass $class,
        &$namespaceName = null,
        &$className = null
    ) {
        $this->typhoon->generateFromClass(func_get_args());

        return $this->generateFromFile(
            $class->getName(),
            $class->getFileName(),
            $namespaceName,
            $className
        );
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
     * @param ReflectionMethod $method
     * @param ClassDefinition $classDefinition
     */
    protected function generateMethod(
        ReflectionMethod $method,
        ClassDefinition $classDefinition
    ) {
        $this->typhoon->generateMethod(func_get_args());

        $methodName = $method->getName();
        if ('__construct' === $methodName) {
            $methodName = 'validateConstructor';
        }

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
        $this->typhoon->parameterList(func_get_args());

        $blockComment = $method->getDocComment();
        if (false === $blockComment) {
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
        $this->typhoon->classNameResolver(func_get_args());

        return new ParameterListClassNameResolver(
            new ObjectTypeClassNameResolver(
                $classDefinition->classNameResolver()
            )
        );
    }

    /**
     * @param ReflectionMethod $method
     *
     * @return ParameterListClassNameResolver
     */
    protected function reflectionResolver(ReflectionMethod $method) {
        $this->typhoon->reflectionResolver(func_get_args());

        return new ParameterListReflectionResolver($method);
    }

    /**
     * @param string $content
     * @param integer $depth
     *
     * @return string
     */
    protected function indent($content, $depth = 1) {
        $this->typhoon->indent(func_get_args());

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

    private $parser;
    private $compiler;
    private $classMapper;
    private $isolator;
    private $typhoon;
}
