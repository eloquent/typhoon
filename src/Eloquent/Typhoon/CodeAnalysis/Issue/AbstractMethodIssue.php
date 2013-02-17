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
use Eloquent\Typhoon\ClassMapper\MethodDefinition;
use Eloquent\Typhoon\TypeCheck\TypeCheck;

abstract class AbstractMethodIssue extends AbstractClassIssue implements MethodIssueInterface
{
    /**
     * @param ClassDefinition    $classDefinition
     * @param MethodDefinition   $methodDefinition
     * @param IssueSeverity|null $severity
     */
    public function __construct(
        ClassDefinition $classDefinition,
        MethodDefinition $methodDefinition,
        IssueSeverity $severity = null
    ) {
        $this->typeCheck = TypeCheck::get(__CLASS__, func_get_args());
        parent::__construct($classDefinition, $severity);

        $this->methodDefinition = $methodDefinition;
    }

    /**
     * @return MethodDefinition
     */
    public function methodDefinition()
    {
        $this->typeCheck->methodDefinition(func_get_args());

        return $this->methodDefinition;
    }

    private $methodDefinition;
    private $typeCheck;
}
