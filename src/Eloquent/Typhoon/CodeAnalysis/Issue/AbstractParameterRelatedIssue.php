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

abstract class AbstractParameterRelatedIssue extends AbstractMethodRelatedIssue implements ParameterRelatedIssueInterface
{
    /**
     * @param ClassDefinition    $classDefinition
     * @param MethodDefinition   $methodDefinition
     * @param string             $parameterName
     * @param IssueSeverity|null $severity
     */
    public function __construct(
        ClassDefinition $classDefinition,
        MethodDefinition $methodDefinition,
        $parameterName,
        IssueSeverity $severity = null
    ) {
        $this->typeCheck = TypeCheck::get(__CLASS__, func_get_args());
        parent::__construct($classDefinition, $methodDefinition, $severity);

        $this->parameterName = $parameterName;
    }

    /**
     * @return string
     */
    public function parameterName()
    {
        $this->typeCheck->parameterName(func_get_args());

        return $this->parameterName;
    }

    private $parameterName;
    private $typeCheck;
}
