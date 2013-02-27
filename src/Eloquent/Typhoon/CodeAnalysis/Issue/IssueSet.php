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

use Eloquent\Cosmos\ClassName;
use Eloquent\Typhoon\TypeCheck\TypeCheck;

class IssueSet
{
    /**
     * @param array<IssueInterface> $issues
     */
    public function __construct(array $issues = array())
    {
        $this->typeCheck = TypeCheck::get(__CLASS__, func_get_args());
        $this->issues = $issues;
    }

    /**
     * @return array<IssueInterface>
     */
    public function issues()
    {
        $this->typeCheck->issues(func_get_args());

        return $this->issues;
    }

    /**
     * @param IssueSeverity $severity
     *
     * @return array<IssueInterface>
     */
    public function issuesBySeverity(IssueSeverity $severity)
    {
        $this->typeCheck->issuesBySeverity(func_get_args());

        $issues = array();
        foreach ($this->issues() as $issue) {
            if ($issue->severity() === $severity) {
                $issues[] = $issue;
            }
        }

        return $issues;
    }

    /**
     * @param IssueSeverity $severity
     *
     * @return array<ClassName>
     */
    public function classNamesBySeverity(IssueSeverity $severity)
    {
        $this->typeCheck->classNamesBySeverity(func_get_args());

        $classNames = array();
        foreach ($this->issuesBySeverity($severity) as $issue) {
            if (
                $issue instanceof ClassRelatedIssueInterface &&
                !in_array(
                    $issue->classDefinition()->className(),
                    $classNames
                )
            ) {
                $classNames[] = $issue->classDefinition()->className();
            }
        }
        sort($classNames, SORT_STRING);

        return $classNames;
    }

    /**
     * @param IssueSeverity $severity
     * @param ClassName     $className
     *
     * @return array<IssueInterface>
     */
    public function classIssuesBySeverityAndClass(
        IssueSeverity $severity,
        ClassName $className
    ) {
        $this->typeCheck->classIssuesBySeverityAndClass(func_get_args());

        $issues = array();
        foreach ($this->issuesBySeverity($severity) as $issue) {
            if (
                $issue instanceof ClassIssueInterface &&
                $issue->classDefinition()->className()->isEqualTo($className)
            ) {
                $issues[] = $issue;
            }
        }

        return $issues;
    }

    /**
     * @param IssueSeverity $severity
     * @param ClassName     $className
     *
     * @return array<IssueInterface>
     */
    public function methodRelatedIssuesBySeverityAndClass(
        IssueSeverity $severity,
        ClassName $className
    ) {
        $this->typeCheck->methodRelatedIssuesBySeverityAndClass(func_get_args());

        $issues = array();
        foreach ($this->issuesBySeverity($severity) as $issue) {
            if (
                $issue instanceof MethodRelatedIssueInterface &&
                $issue->classDefinition()->className()->isEqualTo($className)
            ) {
                $issues[$issue->methodDefinition()->name()][] = $issue;
            }
        }

        return $issues;
    }

    /**
     * @return boolean
     */
    public function isError()
    {
        $this->typeCheck->isError(func_get_args());

        return count($this->issuesBySeverity(IssueSeverity::ERROR())) > 0;
    }

    private $issues;
    private $typeCheck;
}
