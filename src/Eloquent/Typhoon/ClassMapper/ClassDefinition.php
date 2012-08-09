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

class ClassDefinition
{
    /**
     * @param string $className
     * @param string|null $namespaceName
     * @param array<string,string|null> $usedClasses
     */
    public function __construct(
        $className,
        $namespaceName = null,
        array $usedClasses = array()
    ) {
        $this->className = $className;
        $this->namespaceName = $namespaceName;
        $this->usedClasses = $usedClasses;
    }

    /**
     * @return string
     */
    public function className()
    {
        return $this->className;
    }

    /**
     * @return string
     */
    public function canonicalClassName()
    {
        return $this->classNameResolver()->resolve(
            $this->className()
        );
    }

    /**
     * @return string|null
     */
    public function namespaceName()
    {
        return $this->namespaceName;
    }

    /**
     * @return array<string,string|null>
     */
    public function usedClasses()
    {
        return $this->usedClasses;
    }

    /**
     * @return ClassNameResolver
     */
    public function classNameResolver()
    {
        return new ClassNameResolver(
            $this->namespaceName(),
            $this->usedClasses()
        );
    }

    private $className;
    private $namespaceName;
    private $usedClasses;
}
