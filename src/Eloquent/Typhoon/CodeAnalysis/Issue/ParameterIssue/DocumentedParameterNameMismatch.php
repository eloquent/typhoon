<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\CodeAnalysis\Issue\ParameterIssue;

use Eloquent\Typhoon\ClassMapper\ClassDefinition;
use Eloquent\Typhoon\ClassMapper\MethodDefinition;
use Eloquent\Typhoon\CodeAnalysis\Issue\AbstractParameterRelatedIssue;
use Eloquent\Typhoon\CodeAnalysis\Issue\IssueSeverity;
use Eloquent\Typhoon\CodeAnalysis\Issue\IssueVisitorInterface;
use Eloquent\Typhoon\CodeAnalysis\Issue\ParameterIssueInterface;
use Eloquent\Typhoon\TypeCheck\TypeCheck;

class DocumentedParameterNameMismatch extends AbstractParameterRelatedIssue implements ParameterIssueInterface
{
    /**
     * @param ClassDefinition    $classDefinition
     * @param MethodDefinition   $methodDefinition
     * @param string             $parameterName
     * @param string             $documentedParameterName
     * @param IssueSeverity|null $severity
     */
    public function __construct(
        ClassDefinition $classDefinition,
        MethodDefinition $methodDefinition,
        $parameterName,
        $documentedParameterName,
        IssueSeverity $severity = null
    ) {
        $this->typeCheck = TypeCheck::get(__CLASS__, func_get_args());
        parent::__construct(
            $classDefinition,
            $methodDefinition,
            $parameterName,
            $severity
        );

        $this->documentedParameterName = $documentedParameterName;
    }

    /**
     * @return string
     */
    public function documentedParameterName()
    {
        $this->typeCheck->documentedParameterName(func_get_args());

        return $this->documentedParameterName;
    }

    /**
     * @param IssueVisitorInterface $visitor
     *
     * @return mixed
     */
    public function accept(IssueVisitorInterface $visitor)
    {
        $this->typeCheck->accept(func_get_args());

        return $visitor->visitDocumentedParameterNameMismatch($this);
    }

    private $documentedParameterName;
    private $typeCheck;
}
