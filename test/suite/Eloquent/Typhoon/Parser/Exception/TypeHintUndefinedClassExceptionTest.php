<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Parser\Exception;

use Eloquent\Cosmos\ClassName;
use Eloquent\Typhoon\TestCase\MultiGenerationTestCase;
use Phake;

class TypeHintUndefinedClassExceptionTest extends MultiGenerationTestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->_className = ClassName::fromString('\foo');
        $this->_parameter = Phake::mock('ReflectionParameter');
        $this->_previous = Phake::mock('Exception');

        Phake::when($this->_parameter)
            ->getName(Phake::anyParameters())
            ->thenReturn('baz')
        ;
        Phake::when($this->_parameter)
            ->__toString(Phake::anyParameters())
            ->thenReturn('Parameter #1 [ <required> qux\doom $className ]')
        ;
    }

    public function testException()
    {
        $exception = new TypeHintUndefinedClassException(
            $this->_className,
            'bar',
            $this->_parameter,
            $this->_previous
        );

        $this->assertSame('Unable to resolve type hint of \qux\doom for parameter $baz in method \foo::bar().', $exception->getMessage());
        $this->assertSame($this->_className, $exception->className());
        $this->assertSame('bar', $exception->functionName());
        $this->assertSame($this->_parameter, $exception->parameter());
        $this->assertEquals(ClassName::fromString('\qux\doom'), $exception->parameterClassName());
        $this->assertSame(0, $exception->getCode());
        $this->assertSame($this->_previous, $exception->getPrevious());
    }

    public function testExceptionWithoutClassName()
    {
        $exception = new TypeHintUndefinedClassException(
            null,
            'bar',
            $this->_parameter
        );

        $this->assertSame('Unable to resolve type hint of \qux\doom for parameter $baz in function bar().', $exception->getMessage());
    }

    public function testExceptionWithUnexpectedParameterFormat()
    {
        Phake::when($this->_parameter)
            ->__toString(Phake::anyParameters())
            ->thenReturn('qux')
        ;
        $exception = new TypeHintUndefinedClassException(
            null,
            'bar',
            $this->_parameter
        );

        $this->assertSame('Unable to resolve type hint of unknown class for parameter $baz in function bar().', $exception->getMessage());
        $this->assertNull($exception->parameterClassName());
    }
}
