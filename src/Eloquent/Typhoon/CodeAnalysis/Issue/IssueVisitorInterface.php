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

interface IssueVisitorInterface
{
    /**
     * @param ClassIssue\MissingConstructorCall $issue
     *
     * @return mixed
     */
    public function visitMissingConstructorCall(ClassIssue\MissingConstructorCall $issue);

    /**
     * @param ClassIssue\MissingProperty $issue
     *
     * @return mixed
     */
    public function visitMissingProperty(ClassIssue\MissingProperty $issue);

    /**
     * @param MethodIssue\InadmissibleMethodCall $issue
     *
     * @return mixed
     */
    public function visitInadmissibleMethodCall(MethodIssue\InadmissibleMethodCall $issue);

    /**
     * @param MethodIssue\MissingMethodCall $issue
     *
     * @return mixed
     */
    public function visitMissingMethodCall(MethodIssue\MissingMethodCall $issue);

    /**
     * @param ParameterIssue\DocumentedParameterByReferenceMismatch $issue
     *
     * @return mixed
     */
    public function visitDocumentedParameterByReferenceMismatch(ParameterIssue\DocumentedParameterByReferenceMismatch $issue);
}
