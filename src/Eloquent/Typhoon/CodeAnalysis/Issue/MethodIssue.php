<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\CodeAnalysis\Issue;

use Eloquent\Typhoon\ClassMapper\ClassDefinition;
use Eloquent\Typhoon\ClassMapper\MethodDefinition;
use Eloquent\Typhoon\TypeCheck\TypeCheck;

abstract class MethodIssue implements ClassRelatedIssue
{
    /**
     * @param ClassDefinition  $classDefinition
     * @param MethodDefinition $methodDefinition
     */
    public function __construct(
        ClassDefinition $classDefinition,
        MethodDefinition $methodDefinition
    ) {
        $this->typeCheck = TypeCheck::get(__CLASS__, func_get_args());

        $this->classDefinition = $classDefinition;
        $this->methodDefinition = $methodDefinition;
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

    private $classDefinition;
    private $methodDefinition;
    private $typeCheck;
}
