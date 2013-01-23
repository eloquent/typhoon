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
use Eloquent\Typhoon\TypeCheck\TypeCheck;

abstract class ClassError extends ClassIssue
{
    /**
     * @param ClassDefinition $classDefinition
     */
    public function __construct(ClassDefinition $classDefinition)
    {
        $this->typeCheck = TypeCheck::get(__CLASS__, func_get_args());
        parent::__construct($classDefinition);
    }

    /**
     * @return IssueSeverity
     */
    public function severity()
    {
        $this->typeCheck->severity(func_get_args());

        return IssueSeverity::ERROR();
    }

    private $typeCheck;
}
