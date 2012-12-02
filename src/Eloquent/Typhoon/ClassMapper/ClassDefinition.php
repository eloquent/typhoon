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
use Eloquent\Typhoon\Validators\Typhoon;

class ClassDefinition
{
    /**
     * @param string                    $className
     * @param string|null               $namespaceName
     * @param array<string,string|null> $usedClasses
     */
    public function __construct(
        $className,
        $namespaceName = null,
        array $usedClasses = array()
    ) {
        $this->typhoon = Typhoon::get(__CLASS__, func_get_args());
        $this->className = $className;
        $this->namespaceName = $namespaceName;
        $this->usedClasses = $usedClasses;
    }

    /**
     * @return string
     */
    public function className()
    {
        $this->typhoon->className(func_get_args());

        return $this->className;
    }

    /**
     * @return string
     */
    public function canonicalClassName()
    {
        $this->typhoon->canonicalClassName(func_get_args());

        return $this->classNameResolver()->resolve(
            $this->className()
        );
    }

    /**
     * @return string|null
     */
    public function namespaceName()
    {
        $this->typhoon->namespaceName(func_get_args());

        return $this->namespaceName;
    }

    /**
     * @return array<string,string|null>
     */
    public function usedClasses()
    {
        $this->typhoon->usedClasses(func_get_args());

        return $this->usedClasses;
    }

    /**
     * @return ClassNameResolver
     */
    public function classNameResolver()
    {
        $this->typhoon->classNameResolver(func_get_args());

        return new ClassNameResolver(
            $this->namespaceName(),
            $this->usedClasses()
        );
    }

    private $className;
    private $namespaceName;
    private $usedClasses;
    private $typhoon;
}
