<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\ClassMapper;

use Eloquent\Cosmos\ClassName;
use Eloquent\Cosmos\ClassNameResolver;
use Eloquent\Typhoon\TypeCheck\TypeCheck;
use ReflectionClass;

class ClassDefinition
{
    /**
     * @param ClassName                      $className
     * @param string                         $source
     * @param array<array<ClassName>>|null   $usedClasses
     * @param array<MethodDefinition>|null   $methods
     * @param array<PropertyDefinition>|null $properties
     * @param string|null                    $path
     * @param integer|null                   $lineNumber
     */
    public function __construct(
        ClassName $className,
        $source,
        array $usedClasses = null,
        array $methods = null,
        array $properties = null,
        $path = null,
        $lineNumber = null
    ) {
        $this->typeCheck = TypeCheck::get(__CLASS__, func_get_args());
        if (null === $usedClasses) {
            $usedClasses = array();
        }
        if (null === $methods) {
            $methods = array();
        }
        if (null === $properties) {
            $properties = array();
        }
        if (null === $lineNumber) {
            $lineNumber = 0;
        }

        $this->className = $className->toAbsolute();
        $this->source = $source;
        $this->methods = $methods;
        $this->properties = $properties;
        $this->path = $path;
        $this->lineNumber = $lineNumber;

        if ($className->hasParent()) {
            $namespaceName = $className->parent();
        } else {
            $namespaceName = null;
        }

        $this->classNameResolver = new ClassNameResolver(
            $namespaceName,
            $usedClasses
        );
    }

    /**
     * @return ClassName
     */
    public function className()
    {
        $this->typeCheck->className(func_get_args());

        return $this->className;
    }

    /**
     * @return string
     */
    public function source()
    {
        $this->typeCheck->source(func_get_args());

        return $this->source;
    }

    /**
     * @return array<array<ClassName>>
     */
    public function usedClasses()
    {
        $this->typeCheck->usedClasses(func_get_args());

        return $this->classNameResolver()->usedClasses();
    }

    /**
     * @return array<MethodDefinition>
     */
    public function methods()
    {
        $this->typeCheck->methods(func_get_args());

        return $this->methods;
    }

    /**
     * @param string $name
     *
     * @return boolean
     */
    public function hasMethod($name)
    {
        $this->typeCheck->hasMethod(func_get_args());

        foreach ($this->methods() as $method) {
            if ($method->name() === $name) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $name
     *
     * @return MethodDefinition
     */
    public function method($name)
    {
        $this->typeCheck->method(func_get_args());

        foreach ($this->methods() as $method) {
            if ($method->name() === $name) {
                return $method;
            }
        }

        throw new Exception\UndefinedMethodException($this->className(), $name);
    }

    /**
     * @return array<PropertyDefinition>
     */
    public function properties()
    {
        $this->typeCheck->properties(func_get_args());

        return $this->properties;
    }

    /**
     * @param string $name
     *
     * @return boolean
     */
    public function hasProperty($name)
    {
        $this->typeCheck->hasProperty(func_get_args());

        foreach ($this->properties() as $property) {
            if ($property->name() === $name) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $name
     *
     * @return PropertyDefinition
     */
    public function property($name)
    {
        $this->typeCheck->property(func_get_args());

        foreach ($this->properties() as $property) {
            if ($property->name() === $name) {
                return $property;
            }
        }

        throw new Exception\UndefinedPropertyException($this->className(), $name);
    }

    /**
     * @return string|null
     */
    public function path()
    {
        $this->typeCheck->path(func_get_args());

        return $this->path;
    }

    /**
     * @return integer
     */
    public function lineNumber()
    {
        $this->typeCheck->lineNumber(func_get_args());

        return $this->lineNumber;
    }

    /**
     * @return ClassNameResolver
     */
    public function classNameResolver()
    {
        $this->typeCheck->classNameResolver(func_get_args());

        return $this->classNameResolver;
    }

    /**
     * @return ReflectionClass
     */
    public function createReflector()
    {
        $this->typeCheck->createReflector(func_get_args());

        return new ReflectionClass($this->className()->string());
    }

    private $className;
    private $source;
    private $methods;
    private $properties;
    private $path;
    private $lineNumber;
    private $classNameResolver;
    private $typeCheck;
}
