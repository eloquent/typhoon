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

use Eloquent\Cosmos\ClassName;
use Eloquent\Cosmos\ClassNameResolver;
use Eloquent\Typhoon\TypeCheck\TypeCheck;

class ClassDefinition
{
    /**
     * @param ClassDefinition $left
     * @param ClassDefinition $right
     *
     * @return integer
     */
    public static function compare(ClassDefinition $left, ClassDefinition $right)
    {
        return strcmp(
            $left->className()->string(),
            $right->className()->string()
        );
    }

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

        return $this->classNameResolver;
    }

    private $className;
    private $classNameResolver;
    private $methods;
    private $properties;
    private $typeCheck;
}
