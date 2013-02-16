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
     * @param ClassName                 $className
     * @param array<array<ClassName>>   $usedClasses
     * @param array<MethodDefinition>   $methods
     * @param array<PropertyDefinition> $properties
     */
    public function __construct(
        ClassName $className,
        array $usedClasses = array(),
        array $methods = array(),
        array $properties = array()
    ) {
        $this->typeCheck = TypeCheck::get(__CLASS__, func_get_args());
        $this->className = $className->toAbsolute();
        $this->methods = $methods;
        $this->properties = $properties;

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
    private $classNameResolver;
    private $methods;
    private $properties;
    private $typeCheck;
}
