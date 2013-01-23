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

use Eloquent\Typhoon\TypeCheck\TypeCheck;

class AnalysisResult
{
    /**
     * @param array<Issue\Issue> $issues
     */
    public function __construct(array $issues = array())
    {
        $this->typeCheck = TypeCheck::get(__CLASS__, func_get_args());
        $this->issues = $issues;
    }

    /**
     * @return array<Issue\Issue>
     */
    public function issues()
    {
        $this->typeCheck->issues(func_get_args());

        return $this->issues;
    }

    /**
     * @param Issue\IssueSeverity $severity
     *
     * @return array<Issue\Issue>
     */
    public function issuesBySeverity(Issue\IssueSeverity $severity)
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
     * @param Issue\IssueSeverity $severity
     *
     * @return array<string,array<Issue\Issue>>
     */
    public function issuesBySeverityByClass(Issue\IssueSeverity $severity)
    {
        $this->typeCheck->issuesBySeverityByClass(func_get_args());

        $issues = array();
        foreach ($this->issuesBySeverity($severity) as $issue) {
            if ($issue instanceof Issue\ClassRelatedIssue) {
                $issues[$issue->classDefinition()->className()->string()][] =
                    $issue
                ;
            }
        }
        ksort($issues, SORT_STRING);

        return $issues;
    }

    /**
     * @return boolean
     */
    public function isError()
    {
        $this->typeCheck->isError(func_get_args());

        return count($this->issuesBySeverity(Issue\IssueSeverity::ERROR())) > 0;
    }

    private $issues;
    private $typeCheck;
}
