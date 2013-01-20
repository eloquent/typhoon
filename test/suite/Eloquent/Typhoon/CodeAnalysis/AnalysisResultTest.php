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

use Eloquent\Typhoon\TestCase\MultiGenerationTestCase;
use Phake;

class AnalysisResultTest extends MultiGenerationTestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->_classesMissingConstructorCall = array(
            Phake::mock('Eloquent\Typhoon\ClassMapper\ClassDefinition'),
            Phake::mock('Eloquent\Typhoon\ClassMapper\ClassDefinition'),
        );
        $this->_classesMissingProperty = array(
            Phake::mock('Eloquent\Typhoon\ClassMapper\ClassDefinition'),
            Phake::mock('Eloquent\Typhoon\ClassMapper\ClassDefinition'),
        );
        $this->_methodsMissingCall = array(
            array(
                Phake::mock('Eloquent\Typhoon\ClassMapper\ClassDefinition'),
                Phake::mock('Eloquent\Typhoon\ClassMapper\MethodDefinition'),
            ),
            array(
                Phake::mock('Eloquent\Typhoon\ClassMapper\ClassDefinition'),
                Phake::mock('Eloquent\Typhoon\ClassMapper\MethodDefinition'),
            ),
        );
        $this->_result = new AnalysisResult(
            $this->_classesMissingConstructorCall,
            $this->_classesMissingProperty,
            $this->_methodsMissingCall
        );
    }

    public function testConstructor()
    {
        $this->assertSame(
            $this->_classesMissingConstructorCall,
            $this->_result->classesMissingConstructorCall()
        );
        $this->assertSame(
            $this->_classesMissingProperty,
            $this->_result->classesMissingProperty()
        );
        $this->assertSame(
            $this->_methodsMissingCall,
            $this->_result->methodsMissingCall()
        );
    }

    public function testCount()
    {
        $result = new AnalysisResult(array(), array(), array());

        $this->assertSame(6, count($this->_result));
        $this->assertSame(0, count($result));
    }

    public function testIsSuccessful()
    {
        $result = new AnalysisResult(array(), array(), array());

        $this->assertFalse($this->_result->isSuccessful());
        $this->assertTrue($result->isSuccessful());
    }
}
