<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\CodeAnalysis\Issue\ClassRelated;

use Eloquent\Typhoon\CodeAnalysis\Issue\IssueSeverity;
use Eloquent\Typhoon\TestCase\MultiGenerationTestCase;
use Phake;

class MissingConstructorCallTest extends MultiGenerationTestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->_classDefinition = Phake::mock(
            'Eloquent\Typhoon\ClassMapper\ClassDefinition'
        );
        $this->_issue = new MissingConstructorCall(
            $this->_classDefinition
        );
    }

    public function testConstructor()
    {
        $this->assertSame($this->_classDefinition, $this->_issue->classDefinition());
        $this->assertSame(IssueSeverity::ERROR(), $this->_issue->severity());
    }

    public function testAccept()
    {
        $visitor = Phake::mock('Eloquent\Typhoon\CodeAnalysis\Issue\IssueVisitor');
        Phake::when($visitor)
            ->visitMissingConstructorCall(Phake::anyParameters())
            ->thenReturn('foo')
        ;

        $this->assertSame('foo', $this->_issue->accept($visitor));
        Phake::verify($visitor)
            ->visitMissingConstructorCall($this->identicalTo($this->_issue))
        ;
    }
}
