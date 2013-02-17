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

class IssueRenderer implements IssueVisitorInterface
{
    public function __construct()
    {
        $this->typeCheck = TypeCheck::get(__CLASS__, func_get_args());
    }

    /**
     * @param ClassIssue\MissingConstructorCall $issue
     *
     * @return string
     */
    public function visitMissingConstructorCall(ClassIssue\MissingConstructorCall $issue)
    {
        $this->typeCheck->visitMissingConstructorCall(func_get_args());

        return 'Incorrect or missing constructor initialization.';
    }

    /**
     * @param ClassIssue\MissingProperty $issue
     *
     * @return string
     */
    public function visitMissingProperty(ClassIssue\MissingProperty $issue)
    {
        $this->typeCheck->visitMissingProperty(func_get_args());

        return 'Incorrect or missing property definition.';
    }

    /**
     * @param MethodIssue\InadmissibleMethodCall $issue
     *
     * @return string
     */
    public function visitInadmissibleMethodCall(MethodIssue\InadmissibleMethodCall $issue)
    {
        $this->typeCheck->visitInadmissibleMethodCall(func_get_args());

        return 'Type check call should not be present.';
    }

    /**
     * @param MethodIssue\MissingMethodCall $issue
     *
     * @return string
     */
    public function visitMissingMethodCall(MethodIssue\MissingMethodCall $issue)
    {
        $this->typeCheck->visitMissingMethodCall(func_get_args());

        return 'Incorrect or missing type check call.';
    }

    /**
     * @param ParameterIssue\DocumentedParameterByReferenceMismatch $issue
     *
     * @return string
     */
    public function visitDocumentedParameterByReferenceMismatch(ParameterIssue\DocumentedParameterByReferenceMismatch $issue)
    {
        $this->typeCheck->visitDocumentedParameterByReferenceMismatch(func_get_args());

        if ($issue->isByReference()) {
            $nativeVariableType = 'by-reference';
            $documentedVariableType = 'by-value';
        } else {
            $nativeVariableType = 'by-value';
            $documentedVariableType = 'by-reference';
        }

        return sprintf(
            'Parameter $%s is defined as %s but documented as %s.',
            $issue->parameterName(),
            $nativeVariableType,
            $documentedVariableType
        );
    }

    private $typeCheck;
}
