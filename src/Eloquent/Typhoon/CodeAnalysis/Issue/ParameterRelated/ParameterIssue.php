<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\CodeAnalysis\Issue\ParameterRelated;

use Eloquent\Typhoon\ClassMapper\ClassDefinition;
use Eloquent\Typhoon\ClassMapper\MethodDefinition;
use Eloquent\Typhoon\Parameter\Parameter;
use Eloquent\Typhoon\TypeCheck\TypeCheck;

abstract class ParameterIssue implements ParameterRelatedIssue
{
    /**
     * @param ClassDefinition  $classDefinition
     * @param MethodDefinition $methodDefinition
     * @param Parameter        $parameterDefinition
     */
    public function __construct(
        ClassDefinition $classDefinition,
        MethodDefinition $methodDefinition,
        Parameter $parameterDefinition
    ) {
        $this->typeCheck = TypeCheck::get(__CLASS__, func_get_args());

        $this->classDefinition = $classDefinition;
        $this->methodDefinition = $methodDefinition;
        $this->parameterDefinition = $parameterDefinition;
    }

    /**
     * @return ClassDefinition
     */
    public function classDefinition()
    {
        $this->typeCheck->classDefinition(func_get_args());

        return $this->classDefinition;
    }

    /**
     * @return MethodDefinition
     */
    public function methodDefinition()
    {
        $this->typeCheck->methodDefinition(func_get_args());

        return $this->methodDefinition;
    }

    /**
     * @return Parameter
     */
    public function parameterDefinition()
    {
        $this->typeCheck->parameterDefinition(func_get_args());

        return $this->parameterDefinition;
    }

    private $classDefinition;
    private $methodDefinition;
    private $parameterDefinition;
    private $typeCheck;
}
