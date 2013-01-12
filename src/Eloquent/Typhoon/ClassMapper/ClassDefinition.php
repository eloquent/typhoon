<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\ClassMapper;

use Eloquent\Cosmos\ClassNameResolver;
use Eloquent\Typhoon\TypeCheck\TypeCheck;

class ClassDefinition
{
    /**
     * @param string                    $className
     * @param string|null               $namespaceName
     * @param array<string,string|null> $usedClasses
     * @param array<MethodDefinition>   $methods
     * @param array<PropertyDefinition> $properties
     */
    public function __construct(
        $className,
        $namespaceName = null,
        array $usedClasses = array(),
        array $methods = array(),
        array $properties = array()
    ) {
        $this->typeCheck = TypeCheck::get(__CLASS__, func_get_args());
        $this->className = $className;
        $this->namespaceName = $namespaceName;
        $this->usedClasses = $usedClasses;
        $this->methods = $methods;
        $this->properties = $properties;
    }

    /**
     * @return string
     */
    public function className()
    {
        $this->typeCheck->className(func_get_args());

        return $this->className;
    }

    /**
     * @return string
     */
    public function canonicalClassName()
    {
        $this->typeCheck->canonicalClassName(func_get_args());

        return $this->classNameResolver()->resolve(
            $this->className()
        );
    }

    /**
     * @return string|null
     */
    public function namespaceName()
    {
        $this->typeCheck->namespaceName(func_get_args());

        return $this->namespaceName;
    }

    /**
     * @return array<string,string|null>
     */
    public function usedClasses()
    {
        $this->typeCheck->usedClasses(func_get_args());

        return $this->usedClasses;
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
     * @return array<PropertyDefinition>
     */
    public function properties()
    {
        $this->typeCheck->properties(func_get_args());

        return $this->properties;
    }

    /**
     * @return ClassNameResolver
     */
    public function classNameResolver()
    {
        $this->typeCheck->classNameResolver(func_get_args());

        return new ClassNameResolver(
            $this->namespaceName(),
            $this->usedClasses()
        );
    }

    private $className;
    private $namespaceName;
    private $usedClasses;
    private $methods;
    private $properties;
    private $typeCheck;
}
