<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\CodeAnalysis\Issue\ClassRelated;

use Eloquent\Typhoon\ClassMapper\ClassDefinition;
use Eloquent\Typhoon\TypeCheck\TypeCheck;

abstract class ClassIssue implements ClassRelatedIssue
{
    /**
     * @param ClassDefinition $classDefinition
     */
    public function __construct(ClassDefinition $classDefinition)
    {
        $this->typeCheck = TypeCheck::get(__CLASS__, func_get_args());
        $this->classDefinition = $classDefinition;
    }

    /**
     * @return ClassDefinition
     */
    public function classDefinition()
    {
        $this->typeCheck->classDefinition(func_get_args());

        return $this->classDefinition;
    }

    private $classDefinition;
    private $typeCheck;
}
