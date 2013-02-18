<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\CodeAnalysis\Issue\MethodIssue;

use Eloquent\Typhoon\CodeAnalysis\Issue\IssueSeverity;
use Eloquent\Typhoon\TestCase\MultiGenerationTestCase;
use Phake;

/**
 * @covers \Eloquent\Typhoon\CodeAnalysis\Issue\MethodIssue\MissingMethodCall
 * @covers \Eloquent\Typhoon\CodeAnalysis\Issue\AbstractMethodRelatedIssue
 * @covers \Eloquent\Typhoon\CodeAnalysis\Issue\AbstractClassRelatedIssue
 */
class MissingMethodCallTest extends MultiGenerationTestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->_classDefinition = Phake::mock(
            'Eloquent\Typhoon\ClassMapper\ClassDefinition'
        );
        $this->_methodDefinition = Phake::mock(
            'Eloquent\Typhoon\ClassMapper\MethodDefinition'
        );
        $this->_severity = IssueSeverity::WARNING();
        $this->_issue = new MissingMethodCall(
            $this->_classDefinition,
            $this->_methodDefinition,
            $this->_severity
        );
    }

    public function testConstructor()
    {
        $this->assertSame($this->_classDefinition, $this->_issue->classDefinition());
        $this->assertSame($this->_methodDefinition, $this->_issue->methodDefinition());
        $this->assertSame(IssueSeverity::WARNING(), $this->_issue->severity());
    }

    public function testConstructorDefaults()
    {
        $this->_issue = new MissingMethodCall(
            $this->_classDefinition,
            $this->_methodDefinition
        );

        $this->assertSame(IssueSeverity::ERROR(), $this->_issue->severity());
    }

    public function testAccept()
    {
        $visitor = Phake::mock('Eloquent\Typhoon\CodeAnalysis\Issue\IssueVisitorInterface');
        Phake::when($visitor)
            ->visitMissingMethodCall(Phake::anyParameters())
            ->thenReturn('foo')
        ;

        $this->assertSame('foo', $this->_issue->accept($visitor));
        Phake::verify($visitor)
            ->visitMissingMethodCall($this->identicalTo($this->_issue))
        ;
    }
}
