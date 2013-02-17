<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\CodeAnalysis\Issue;

use Eloquent\Typhoon\ClassMapper\ClassDefinition;
use Eloquent\Typhoon\TypeCheck\TypeCheck;

abstract class AbstractClassIssue implements ClassIssueInterface
{
    /**
     * @param ClassDefinition    $classDefinition
     * @param IssueSeverity|null $severity
     */
    public function __construct(
        ClassDefinition $classDefinition,
        IssueSeverity $severity = null
    ) {
        $this->typeCheck = TypeCheck::get(__CLASS__, func_get_args());
        if (null === $severity) {
            $severity = IssueSeverity::ERROR();
        }

        $this->classDefinition = $classDefinition;
        $this->severity = $severity;
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
     * @return IssueSeverity
     */
    public function severity()
    {
        $this->typeCheck->severity(func_get_args());

        return $this->severity;
    }

    private $classDefinition;
    private $severity;
    private $typeCheck;
}
