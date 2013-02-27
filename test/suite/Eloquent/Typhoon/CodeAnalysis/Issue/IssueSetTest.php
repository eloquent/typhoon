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
use Eloquent\Typhoon\ClassMapper\ClassDefinition;
use Eloquent\Typhoon\TestCase\MultiGenerationTestCase;
use Phake;

class IssueSetTest extends MultiGenerationTestCase
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
            __NAMESPACE__.'\AbstractMethodRelatedIssue',
            $this->_classDefinitionA,
            $this->_methodDefinitionA,
            IssueSeverity::WARNING()
        );
        $this->_warningB = Phake::partialMock(
            __NAMESPACE__.'\AbstractMethodRelatedIssue',
            $this->_classDefinitionB,
            $this->_methodDefinitionB,
            IssueSeverity::WARNING()
        );
        $this->_warningC = Phake::partialMock(
            __NAMESPACE__.'\ClassIssue\MissingConstructorCall',
            $this->_classDefinitionA,
            IssueSeverity::WARNING()
        );
        $this->_warningD = Phake::partialMock(
            __NAMESPACE__.'\ClassIssue\MissingConstructorCall',
            $this->_classDefinitionC,
            IssueSeverity::WARNING()
        );
        $this->_errorA = Phake::partialMock(
            __NAMESPACE__.'\AbstractMethodRelatedIssue',
            $this->_classDefinitionA,
            $this->_methodDefinitionA
        );
        $this->_errorB = Phake::partialMock(
            __NAMESPACE__.'\AbstractMethodRelatedIssue',
            $this->_classDefinitionB,
            $this->_methodDefinitionB
        );
        $this->_errorC = Phake::partialMock(
            __NAMESPACE__.'\ClassIssue\MissingConstructorCall',
            $this->_classDefinitionA
        );
        $this->_errorD = Phake::partialMock(
            __NAMESPACE__.'\ClassIssue\MissingConstructorCall',
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
        $this->_set = new IssueSet($this->_issues);
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
        $this->assertSame($this->_issues, $this->_set->issues());
    }

    public function testConstructorDefaults()
    {
        $this->_set = new IssueSet;

        $this->assertSame(array(), $this->_set->issues());
    }

    public function testIssuesBySeverity()
    {
        $this->assertSame(array(
            $this->_warningA,
            $this->_warningB,
            $this->_warningC,
            $this->_warningD,
        ), $this->_set->issuesBySeverity(IssueSeverity::WARNING()));
        $this->assertSame(array(
            $this->_errorA,
            $this->_errorB,
            $this->_errorC,
            $this->_errorD,
        ), $this->_set->issuesBySeverity(IssueSeverity::ERROR()));
    }

    public function testClassNamesBySeverity()
    {
        $this->assertSame(array(
            $this->_classNameA,
            $this->_classNameB,
            $this->_classNameC,
        ), $this->_set->classNamesBySeverity(IssueSeverity::WARNING()));
        $this->assertSame(array(
            $this->_classNameA,
            $this->_classNameB,
            $this->_classNameD,
        ), $this->_set->classNamesBySeverity(IssueSeverity::ERROR()));
    }

    public function testClassIssuesBySeverityAndClass()
    {
        $this->assertSame(array(
            $this->_warningC,
        ), $this->_set->classIssuesBySeverityAndClass(IssueSeverity::WARNING(), $this->_classNameA));
        $this->assertSame(array(
            $this->_errorC,
        ), $this->_set->classIssuesBySeverityAndClass(IssueSeverity::ERROR(), $this->_classNameA));
        $this->assertSame(array(
            $this->_warningD,
        ), $this->_set->classIssuesBySeverityAndClass(IssueSeverity::WARNING(), $this->_classNameC));
        $this->assertSame(array(
            $this->_errorD,
        ), $this->_set->classIssuesBySeverityAndClass(IssueSeverity::ERROR(), $this->_classNameD));
        $this->assertSame(
            array(),
            $this->_set->classIssuesBySeverityAndClass(IssueSeverity::WARNING(), $this->_classNameB)
        );
        $this->assertSame(
            array(),
            $this->_set->classIssuesBySeverityAndClass(IssueSeverity::ERROR(), $this->_classNameB)
        );
    }

    public function testMethodRelatedIssuesBySeverityAndClass()
    {
        $this->assertSame(array(
            'A' => array($this->_warningA),
        ), $this->_set->methodRelatedIssuesBySeverityAndClass(IssueSeverity::WARNING(), $this->_classNameA));
        $this->assertSame(array(
            'B' => array($this->_warningB),
        ), $this->_set->methodRelatedIssuesBySeverityAndClass(IssueSeverity::WARNING(), $this->_classNameB));
        $this->assertSame(array(
            'A' => array($this->_errorA),
        ), $this->_set->methodRelatedIssuesBySeverityAndClass(IssueSeverity::ERROR(), $this->_classNameA));
        $this->assertSame(array(
            'B' => array($this->_errorB),
        ), $this->_set->methodRelatedIssuesBySeverityAndClass(IssueSeverity::ERROR(), $this->_classNameB));
    }

    public function testIsError()
    {
        $successResult = new IssueSet;
        $warningResult = new IssueSet(array(
            $this->_warningA,
        ));
        $errorResult = new IssueSet(array(
            $this->_warningA,
            $this->_errorA,
        ));

        $this->assertFalse($successResult->isError());
        $this->assertFalse($warningResult->isError());
        $this->assertTrue($errorResult->isError());
    }
}
