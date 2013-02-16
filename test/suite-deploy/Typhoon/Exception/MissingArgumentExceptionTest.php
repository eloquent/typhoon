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

class MissingArgumentExceptionTest extends PHPUnit_Framework_TestCase
{
    public function testException()
    {
        $previous = Phake::mock('Exception');
        $exception = new MissingArgumentException(
            'foo',
            111,
            'bar',
            $previous
        );

        $this->assertSame(
            "Missing argument for parameter 'foo' at index 111. Expected 'bar'.",
            $exception->getMessage()
        );
        $this->assertSame('foo', $exception->parameterName());
        $this->assertSame(111, $exception->index());
        $this->assertSame('bar', $exception->expectedType());
        $this->assertSame(0, $exception->getCode());
        $this->assertSame($previous, $exception->getPrevious());
    }
}
