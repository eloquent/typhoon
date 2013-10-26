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

use Eloquent\Typhoon\TypeCheck\TypeCheck;

class IssueFixer implements IssueVisitorInterface
{
    public function __construct()
    {
        $this->typeCheck = TypeCheck::get(__CLASS__, func_get_args());
    }

    /**
     * @param IssueSet $issues
     *
     * @return array<string>
     */
    public function fix(IssueSet $issues)
    {

    }

    /**
     * @param IssueSet $issues
     */
    public function visitIssueSet(IssueSet $issues)
    {
        $this->typeCheck->visitIssueSet(func_get_args());

        foreach ($issues->issues() as $issue) {
            $issue->accept($this);
        }
    }

    /**
     * @param ClassIssue\MissingConstructorCall $issue
     */
    public function visitMissingConstructorCall(ClassIssue\MissingConstructorCall $issue)
    {
        $this->typeCheck->visitMissingConstructorCall(func_get_args());
    }

    /**
     * @param ClassIssue\MissingProperty $issue
     */
    public function visitMissingProperty(ClassIssue\MissingProperty $issue)
    {
        $this->typeCheck->visitMissingProperty(func_get_args());
    }

    /**
     * @param MethodIssue\InadmissibleMethodCall $issue
     */
    public function visitInadmissibleMethodCall(MethodIssue\InadmissibleMethodCall $issue)
    {
        $this->typeCheck->visitInadmissibleMethodCall(func_get_args());
    }

    /**
     * @param MethodIssue\MissingMethodCall $issue
     */
    public function visitMissingMethodCall(MethodIssue\MissingMethodCall $issue)
    {
        $this->typeCheck->visitMissingMethodCall(func_get_args());
    }

    /**
     * @param ParameterIssue\DefinedParameterVariableLength $issue
     */
    public function visitDefinedParameterVariableLength(ParameterIssue\DefinedParameterVariableLength $issue)
    {
        $this->typeCheck->visitDefinedParameterVariableLength(func_get_args());
    }

    /**
     * @param ParameterIssue\DocumentedParameterByReferenceMismatch $issue
     */
    public function visitDocumentedParameterByReferenceMismatch(ParameterIssue\DocumentedParameterByReferenceMismatch $issue)
    {
        $this->typeCheck->visitDocumentedParameterByReferenceMismatch(func_get_args());

        throw new Exception\UnfixableIssueException($issue);
    }

    /**
     * @param ParameterIssue\DocumentedParameterNameMismatch $issue
     */
    public function visitDocumentedParameterNameMismatch(ParameterIssue\DocumentedParameterNameMismatch $issue)
    {
        $this->typeCheck->visitDocumentedParameterNameMismatch(func_get_args());

        throw new Exception\UnfixableIssueException($issue);
    }

    /**
     * @param ParameterIssue\DocumentedParameterTypeMismatch $issue
     */
    public function visitDocumentedParameterTypeMismatch(ParameterIssue\DocumentedParameterTypeMismatch $issue)
    {
        $this->typeCheck->visitDocumentedParameterTypeMismatch(func_get_args());

        throw new Exception\UnfixableIssueException($issue);
    }

    /**
     * @param ParameterIssue\DocumentedParameterUndefined $issue
     */
    public function visitDocumentedParameterUndefined(ParameterIssue\DocumentedParameterUndefined $issue)
    {
        $this->typeCheck->visitDocumentedParameterUndefined(func_get_args());

        throw new Exception\UnfixableIssueException($issue);
    }

    /**
     * @param ParameterIssue\UndocumentedParameter $issue
     */
    public function visitUndocumentedParameter(ParameterIssue\UndocumentedParameter $issue)
    {
        $this->typeCheck->visitUndocumentedParameter(func_get_args());

        throw new Exception\UnfixableIssueException($issue);
    }

    private $typeCheck;
}
