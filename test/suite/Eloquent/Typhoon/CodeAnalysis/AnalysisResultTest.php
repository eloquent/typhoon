<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2013 Erin Millard
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

        $this->_classNameA = ClassName::fromString('\A');
        $this->_classNameB = ClassName::fromString('\B');
        $this->_classNameC = ClassName::fromString('\C');
        $this->_classNameD = ClassName::fromString('\D');
        $this->_classDefinitionA = new ClassDefinition($this->_classNameA);
        $this->_classDefinitionB = new ClassDefinition($this->_classNameB);
        $this->_classDefinitionC = new ClassDefinition($this->_classNameC);
        $this->_classDefinitionD = new ClassDefinition($this->_classNameD);
        $this->_methodDefinitionA = $this->methodDefinitionFixture('A');
        $this->_methodDefinitionB = $this->methodDefinitionFixture('B');

        $this->_warningA = Phake::partialMock(
            __NAMESPACE__.'\Issue\AbstractMethodRelatedIssue',
            $this->_classDefinitionA,
            $this->_methodDefinitionA,
            Issue\IssueSeverity::WARNING()
        );
        $this->_warningB = Phake::partialMock(
            __NAMESPACE__.'\Issue\AbstractMethodRelatedIssue',
            $this->_classDefinitionB,
            $this->_methodDefinitionB,
            Issue\IssueSeverity::WARNING()
        );
        $this->_warningC = Phake::partialMock(
            __NAMESPACE__.'\Issue\ClassIssue\MissingConstructorCall',
            $this->_classDefinitionA,
            Issue\IssueSeverity::WARNING()
        );
        $this->_warningD = Phake::partialMock(
            __NAMESPACE__.'\Issue\ClassIssue\MissingConstructorCall',
            $this->_classDefinitionC,
            Issue\IssueSeverity::WARNING()
        );
        $this->_errorA = Phake::partialMock(
            __NAMESPACE__.'\Issue\AbstractMethodRelatedIssue',
            $this->_classDefinitionA,
            $this->_methodDefinitionA
        );
        $this->_errorB = Phake::partialMock(
            __NAMESPACE__.'\Issue\AbstractMethodRelatedIssue',
            $this->_classDefinitionB,
            $this->_methodDefinitionB
        );
        $this->_errorC = Phake::partialMock(
            __NAMESPACE__.'\Issue\ClassIssue\MissingConstructorCall',
            $this->_classDefinitionA
        );
        $this->_errorD = Phake::partialMock(
            __NAMESPACE__.'\Issue\ClassIssue\MissingConstructorCall',
            $this->_classDefinitionD
        );

        $this->_issues = array(
            $this->_warningA,
            $this->_warningB,
            $this->_warningC,
            $this->_warningD,
            $this->_errorA,
            $this->_errorB,
            $this->_errorC,
            $this->_errorD,
        );
        $this->_result = new AnalysisResult($this->_issues);
    }

    protected function methodDefinitionFixture($name)
    {
        $methodDefinition = Phake::mock('Eloquent\Typhoon\ClassMapper\MethodDefinition');
        Phake::when($methodDefinition)
            ->name(Phake::anyParameters())
            ->thenReturn($name)
        ;

        return $methodDefinition;
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
            $this->_warningC,
            $this->_warningD,
        ), $this->_result->issuesBySeverity(Issue\IssueSeverity::WARNING()));
        $this->assertSame(array(
            $this->_errorA,
            $this->_errorB,
            $this->_errorC,
            $this->_errorD,
        ), $this->_result->issuesBySeverity(Issue\IssueSeverity::ERROR()));
    }

    public function testClassNamesBySeverity()
    {
        $this->assertSame(array(
            $this->_classNameA,
            $this->_classNameB,
            $this->_classNameC,
        ), $this->_result->classNamesBySeverity(Issue\IssueSeverity::WARNING()));
        $this->assertSame(array(
            $this->_classNameA,
            $this->_classNameB,
            $this->_classNameD,
        ), $this->_result->classNamesBySeverity(Issue\IssueSeverity::ERROR()));
    }

    public function testClassIssuesBySeverityAndClass()
    {
        $this->assertSame(array(
            $this->_warningC,
        ), $this->_result->classIssuesBySeverityAndClass(Issue\IssueSeverity::WARNING(), $this->_classNameA));
        $this->assertSame(array(
            $this->_errorC,
        ), $this->_result->classIssuesBySeverityAndClass(Issue\IssueSeverity::ERROR(), $this->_classNameA));
        $this->assertSame(array(
            $this->_warningD,
        ), $this->_result->classIssuesBySeverityAndClass(Issue\IssueSeverity::WARNING(), $this->_classNameC));
        $this->assertSame(array(
            $this->_errorD,
        ), $this->_result->classIssuesBySeverityAndClass(Issue\IssueSeverity::ERROR(), $this->_classNameD));
        $this->assertSame(
            array(),
            $this->_result->classIssuesBySeverityAndClass(Issue\IssueSeverity::WARNING(), $this->_classNameB)
        );
        $this->assertSame(
            array(),
            $this->_result->classIssuesBySeverityAndClass(Issue\IssueSeverity::ERROR(), $this->_classNameB)
        );
    }

    public function testMethodRelatedIssuesBySeverityAndClass()
    {
        $this->assertSame(array(
            'A' => array($this->_warningA),
        ), $this->_result->methodRelatedIssuesBySeverityAndClass(Issue\IssueSeverity::WARNING(), $this->_classNameA));
        $this->assertSame(array(
            'B' => array($this->_warningB),
        ), $this->_result->methodRelatedIssuesBySeverityAndClass(Issue\IssueSeverity::WARNING(), $this->_classNameB));
        $this->assertSame(array(
            'A' => array($this->_errorA),
        ), $this->_result->methodRelatedIssuesBySeverityAndClass(Issue\IssueSeverity::ERROR(), $this->_classNameA));
        $this->assertSame(array(
            'B' => array($this->_errorB),
        ), $this->_result->methodRelatedIssuesBySeverityAndClass(Issue\IssueSeverity::ERROR(), $this->_classNameB));
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
