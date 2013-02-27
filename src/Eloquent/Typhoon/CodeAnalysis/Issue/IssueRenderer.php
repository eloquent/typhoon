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

use Eloquent\Typhax\Renderer\TypeRenderer;
use Eloquent\Typhoon\TypeCheck\TypeCheck;

class IssueRenderer implements IssueVisitorInterface
{
    /**
     * @param TypeRenderer|null $typeRenderer
     */
    public function __construct(TypeRenderer $typeRenderer = null)
    {
        $this->typeCheck = TypeCheck::get(__CLASS__, func_get_args());
        if (null === $typeRenderer) {
            $typeRenderer = new TypeRenderer;
        }

        $this->typeRenderer = $typeRenderer;
    }

    /**
     * @return TypeRenderer
     */
    public function typeRenderer()
    {
        $this->typeCheck->typeRenderer(func_get_args());

        return $this->typeRenderer;
    }

    /**
     * @param IssueSet $issues
     *
     * @return array<string>
     */
    public function visitIssueSet(IssueSet $issues)
    {
        $this->typeCheck->visitIssueSet(func_get_args());

        $rendered = array();
        foreach ($issues->issues() as $issue) {
            $rendered[] = $issue->accept($this);
        }

        return $rendered;
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
     * @param ParameterIssue\DefinedParameterVariableLength $issue
     *
     * @return string
     */
    public function visitDefinedParameterVariableLength(ParameterIssue\DefinedParameterVariableLength $issue)
    {
        $this->typeCheck->visitDefinedParameterVariableLength(func_get_args());

        return sprintf(
            'Variable-length parameter $%s should only be documented, not defined.',
            $issue->parameterName()
        );
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

    /**
     * @param ParameterIssue\DocumentedParameterNameMismatch $issue
     *
     * @return string
     */
    public function visitDocumentedParameterNameMismatch(ParameterIssue\DocumentedParameterNameMismatch $issue)
    {
        $this->typeCheck->visitDocumentedParameterNameMismatch(func_get_args());

        return sprintf(
            'Documented parameter name $%s does not match defined parameter name $%s.',
            $issue->documentedParameterName(),
            $issue->parameterName()
        );
    }

    /**
     * @param ParameterIssue\DocumentedParameterTypeMismatch $issue
     *
     * @return string
     */
    public function visitDocumentedParameterTypeMismatch(ParameterIssue\DocumentedParameterTypeMismatch $issue)
    {
        $this->typeCheck->visitDocumentedParameterTypeMismatch(func_get_args());

        return sprintf(
            "Documented type '%s' is not correct for defined type '%s' of parameter $%s.",
            $issue->documentedType()->accept($this->typeRenderer()),
            $issue->type()->accept($this->typeRenderer()),
            $issue->parameterName()
        );
    }

    /**
     * @param ParameterIssue\DocumentedParameterUndefined $issue
     *
     * @return string
     */
    public function visitDocumentedParameterUndefined(ParameterIssue\DocumentedParameterUndefined $issue)
    {
        $this->typeCheck->visitDocumentedParameterUndefined(func_get_args());

        return sprintf(
            'Documented parameter $%s not defined.',
            $issue->parameterName()
        );
    }

    /**
     * @param ParameterIssue\UndocumentedParameter $issue
     *
     * @return string
     */
    public function visitUndocumentedParameter(ParameterIssue\UndocumentedParameter $issue)
    {
        $this->typeCheck->visitUndocumentedParameter(func_get_args());

        return sprintf(
            'Parameter $%s is not documented.',
            $issue->parameterName()
        );
    }

    private $typeRenderer;
    private $typeCheck;
}
