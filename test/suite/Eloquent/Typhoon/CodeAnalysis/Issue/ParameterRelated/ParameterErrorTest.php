<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\CodeAnalysis\Issue\ParameterRelated;

use Eloquent\Typhoon\CodeAnalysis\Issue\IssueSeverity;
use Eloquent\Typhoon\TestCase\MultiGenerationTestCase;
use Phake;

class ParameterErrorTest extends MultiGenerationTestCase
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
        $this->_parameterDefinition = Phake::mock(
            'Eloquent\Typhoon\Parameter\Parameter'
        );
        $this->_issue = Phake::partialMock(
            __NAMESPACE__.'\ParameterError',
            $this->_classDefinition,
            $this->_methodDefinition,
            $this->_parameterDefinition
        );
    }

    public function testConstructor()
    {
        $this->assertSame($this->_classDefinition, $this->_issue->classDefinition());
        $this->assertSame($this->_methodDefinition, $this->_issue->methodDefinition());
        $this->assertSame($this->_parameterDefinition, $this->_issue->parameterDefinition());
        $this->assertSame(IssueSeverity::ERROR(), $this->_issue->severity());
    }
}
