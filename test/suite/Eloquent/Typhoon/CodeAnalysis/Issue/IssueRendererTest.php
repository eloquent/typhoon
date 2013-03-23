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
use Eloquent\Typhax\Type\ArrayType;
use Eloquent\Typhax\Type\MixedType;
use Eloquent\Typhoon\ClassMapper\ClassDefinition;
use Eloquent\Typhoon\ClassMapper\MethodDefinition;
use Eloquent\Typhoon\TestCase\MultiGenerationTestCase;
use Icecave\Pasta\AST\Type\AccessModifier;

class IssueRendererTest extends MultiGenerationTestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->_renderer = new IssueRenderer;

        $this->_className = ClassName::fromString('\foo');
        $this->_methodDefinition = new MethodDefinition(
            $this->_className,
            'bar',
            false,
            false,
            AccessModifier::PUBLIC_(),
            111,
            'baz'
        );
        $this->_classDefinition = new ClassDefinition(
            $this->_className,
            'class foo {}',
            array(),
            array($this->_methodDefinition)
        );
    }

    public function testVisitMissingConstructorCall()
    {
        $issue = new ClassIssue\MissingConstructorCall(
            $this->_classDefinition
        );

        $this->assertSame(
            'Incorrect or missing constructor initialization.',
            $issue->accept($this->_renderer)
        );
    }

    public function testVisitMissingProperty()
    {
        $issue = new ClassIssue\MissingProperty(
            $this->_classDefinition
        );

        $this->assertSame(
            'Incorrect or missing property definition.',
            $issue->accept($this->_renderer)
        );
    }

    public function testVisitInadmissibleMethodCall()
    {
        $issue = new MethodIssue\InadmissibleMethodCall(
            $this->_classDefinition,
            $this->_methodDefinition
        );

        $this->assertSame(
            'Type check call should not be present.',
            $issue->accept($this->_renderer)
        );
    }

    public function testVisitMissingMethodCall()
    {
        $issue = new MethodIssue\MissingMethodCall(
            $this->_classDefinition,
            $this->_methodDefinition
        );

        $this->assertSame(
            'Incorrect or missing type check call.',
            $issue->accept($this->_renderer)
        );
    }

    public function testVisitDefinedParameterVariableLength()
    {
        $issue = new ParameterIssue\DefinedParameterVariableLength(
            $this->_classDefinition,
            $this->_methodDefinition,
            'qux'
        );

        $this->assertSame(
            'Variable-length parameter $qux should only be documented, not defined.',
            $issue->accept($this->_renderer)
        );
    }

    public function testVisitDocumentedParameterByReferenceMismatchByReference()
    {
        $issue = new ParameterIssue\DocumentedParameterByReferenceMismatch(
            $this->_classDefinition,
            $this->_methodDefinition,
            'qux',
            true
        );

        $this->assertSame(
            'Parameter $qux is defined as by-reference but documented as by-value.',
            $issue->accept($this->_renderer)
        );
    }

    public function testVisitDocumentedParameterByReferenceMismatchByValue()
    {
        $issue = new ParameterIssue\DocumentedParameterByReferenceMismatch(
            $this->_classDefinition,
            $this->_methodDefinition,
            'qux',
            false
        );

        $this->assertSame(
            'Parameter $qux is defined as by-value but documented as by-reference.',
            $issue->accept($this->_renderer)
        );
    }

    public function testVisitDocumentedParameterNameMismatch()
    {
        $issue = new ParameterIssue\DocumentedParameterNameMismatch(
            $this->_classDefinition,
            $this->_methodDefinition,
            'qux',
            'doom'
        );

        $this->assertSame(
            'Documented parameter name $doom does not match defined parameter name $qux.',
            $issue->accept($this->_renderer)
        );
    }

    public function testVisitDocumentedParameterTypeMismatch()
    {
        $issue = new ParameterIssue\DocumentedParameterTypeMismatch(
            $this->_classDefinition,
            $this->_methodDefinition,
            'qux',
            new ArrayType,
            new MixedType
        );

        $this->assertSame(
            "Documented type 'mixed' is not correct for defined type 'array' of parameter \$qux.",
            $issue->accept($this->_renderer)
        );
    }

    public function testVisitDocumentedParameterUndefined()
    {
        $issue = new ParameterIssue\DocumentedParameterUndefined(
            $this->_classDefinition,
            $this->_methodDefinition,
            'qux'
        );

        $this->assertSame(
            'Documented parameter $qux not defined.',
            $issue->accept($this->_renderer)
        );
    }

    public function testVisitUndocumentedParameter()
    {
        $issue = new ParameterIssue\UndocumentedParameter(
            $this->_classDefinition,
            $this->_methodDefinition,
            'qux'
        );

        $this->assertSame(
            'Parameter $qux is not documented.',
            $issue->accept($this->_renderer)
        );
    }
}
