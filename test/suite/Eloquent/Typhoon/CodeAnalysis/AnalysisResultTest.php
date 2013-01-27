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

use Eloquent\Cosmos\ClassName;
use Eloquent\Typhoon\ClassMapper\ClassDefinition;
use Eloquent\Typhoon\TestCase\MultiGenerationTestCase;
use Phake;

class AnalysisResultTest extends MultiGenerationTestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->_classDefinitionA = new ClassDefinition(
            ClassName::fromString('\A')
        );
        $this->_classDefinitionB = new ClassDefinition(
            ClassName::fromString('\B')
        );
        $this->_methodDefinition = Phake::mock(
            'Eloquent\Typhoon\ClassMapper\MethodDefinition'
        );

        $this->_warningA = Phake::partialMock(
            __NAMESPACE__.'\Issue\MethodRelated\MethodIssue',
            $this->_classDefinitionA,
            $this->_methodDefinition
        );
        Phake::when($this->_warningA)
            ->severity(Phake::anyParameters())
            ->thenReturn(Issue\IssueSeverity::WARNING())
        ;
        $this->_warningB = Phake::partialMock(
            __NAMESPACE__.'\Issue\MethodRelated\MethodIssue',
            $this->_classDefinitionB,
            $this->_methodDefinition
        );
        Phake::when($this->_warningB)
            ->severity(Phake::anyParameters())
            ->thenReturn(Issue\IssueSeverity::WARNING())
        ;
        $this->_errorA = Phake::partialMock(
            __NAMESPACE__.'\Issue\MethodRelated\MethodError',
            $this->_classDefinitionA,
            $this->_methodDefinition
        );
        $this->_errorB = Phake::partialMock(
            __NAMESPACE__.'\Issue\MethodRelated\MethodError',
            $this->_classDefinitionB,
            $this->_methodDefinition
        );

        $this->_issues = array(
            $this->_warningA,
            $this->_warningB,
            $this->_errorA,
            $this->_errorB,
        );
        $this->_result = new AnalysisResult($this->_issues);
    }

    public function testConstructor()
    {
        $this->assertSame($this->_issues, $this->_result->issues());
    }

    public function testConstructorDefaults()
    {
        $this->_result = new AnalysisResult;

        $this->assertSame(array(), $this->_result->issues());
    }

    public function testIssuesBySeverity()
    {
        $this->assertSame(array(
            $this->_warningA,
            $this->_warningB,
        ), $this->_result->issuesBySeverity(Issue\IssueSeverity::WARNING()));
        $this->assertSame(array(
            $this->_errorA,
            $this->_errorB,
        ), $this->_result->issuesBySeverity(Issue\IssueSeverity::ERROR()));
    }

    public function testIssuesBySeverityByClass()
    {
        $this->assertSame(array(
            '\A' => array($this->_warningA),
            '\B' => array($this->_warningB),
        ), $this->_result->issuesBySeverityByClass(Issue\IssueSeverity::WARNING()));
        $this->assertSame(array(
            '\A' => array($this->_errorA),
            '\B' => array($this->_errorB),
        ), $this->_result->issuesBySeverityByClass(Issue\IssueSeverity::ERROR()));
    }

    public function testIsError()
    {
        $successResult = new AnalysisResult;
        $warningResult = new AnalysisResult(array(
            $this->_warningA,
        ));
        $errorResult = new AnalysisResult(array(
            $this->_warningA,
            $this->_errorA,
        ));

        $this->assertFalse($successResult->isError());
        $this->assertFalse($warningResult->isError());
        $this->assertTrue($errorResult->isError());
    }
}
