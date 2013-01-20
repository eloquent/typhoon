<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\CodeAnalysis;

use Countable;
use Eloquent\Typhoon\ClassMapper\ClassDefinition;
use Eloquent\Typhoon\ClassMapper\MethodDefinition;
use Eloquent\Typhoon\TypeCheck\TypeCheck;

class AnalysisResult implements Countable
{
    /**
     * @param array<ClassDefinition>                         $classesMissingConstructorCall
     * @param array<ClassDefinition>                         $classesMissingProperty
     * @param array<tuple<ClassDefinition,MethodDefinition>> $methodsMissingCall
     */
    public function __construct(
        array $classesMissingConstructorCall,
        array $classesMissingProperty,
        array $methodsMissingCall
    ) {
        $this->typeCheck = TypeCheck::get(__CLASS__, func_get_args());
        $this->classesMissingConstructorCall = $classesMissingConstructorCall;
        $this->classesMissingProperty = $classesMissingProperty;
        $this->methodsMissingCall = $methodsMissingCall;
    }

    /**
     * @return array<ClassDefinition>
     */
    public function classesMissingConstructorCall()
    {
        $this->typeCheck->classesMissingConstructorCall(func_get_args());

        return $this->classesMissingConstructorCall;
    }

    /**
     * @return array<ClassDefinition>
     */
    public function classesMissingProperty()
    {
        $this->typeCheck->classesMissingProperty(func_get_args());

        return $this->classesMissingProperty;
    }

    /**
     * @return array<tuple<ClassDefinition,MethodDefinition>>
     */
    public function methodsMissingCall()
    {
        $this->typeCheck->methodsMissingCall(func_get_args());

        return $this->methodsMissingCall;
    }

    /**
     * @return boolean
     */
    public function isSuccessful()
    {
        $this->typeCheck->isSuccessful(func_get_args());

        return 0 === $this->count();
    }

    /**
     * @return integer
     */
    public function count()
    {
        $this->typeCheck->count(func_get_args());

        return
            count($this->classesMissingConstructorCall()) +
            count($this->classesMissingProperty()) +
            count($this->methodsMissingCall())
        ;
    }

    private $classesMissingConstructorCall;
    private $classesMissingProperty;
    private $methodsMissingCall;
    private $typeCheck;
}
