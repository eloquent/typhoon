<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Typhoon\Exception;

use Phake;
use PHPUnit_Framework_TestCase;

class UnexpectedArgumentValueExceptionTest extends PHPUnit_Framework_TestCase
{
    public function testException()
    {
        $previous = Phake::mock('Exception');
        $exception = new UnexpectedArgumentValueException(
            'foo',
            111,
            2.22,
            'bar',
            $previous
        );

        $this->assertSame(
            "Unexpected argument of type 'float' for parameter 'foo' at index 111. Expected 'bar'.",
            $exception->getMessage()
        );
        $this->assertSame('foo', $exception->parameterName());
        $this->assertSame(111, $exception->index());
        $this->assertSame(2.22, $exception->value());
        $this->assertSame('bar', $exception->expectedType());
        $this->assertSame('float', $exception->unexpectedType());
        $this->assertSame(0, $exception->getCode());
        $this->assertSame($previous, $exception->getPrevious());
        $this->assertInstanceOf('Typhoon\TypeInspector', $exception->typeInspector());
    }
}
