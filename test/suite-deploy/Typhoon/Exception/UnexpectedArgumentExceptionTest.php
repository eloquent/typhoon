<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Typhoon\Exception;

use Phake;
use PHPUnit_Framework_TestCase;

class UnexpectedArgumentExceptionTest extends PHPUnit_Framework_TestCase
{
    public function testException()
    {
        $previous = Phake::mock('Exception');
        $exception = new UnexpectedArgumentException(
            111,
            2.22,
            $previous
        );

        $this->assertSame("Unexpected argument of type 'float' at index 111.", $exception->getMessage());
        $this->assertSame(111, $exception->index());
        $this->assertSame(2.22, $exception->value());
        $this->assertSame('float', $exception->unexpectedType());
        $this->assertSame(0, $exception->getCode());
        $this->assertSame($previous, $exception->getPrevious());
        $this->assertInstanceOf('Typhoon\TypeInspector', $exception->typeInspector());
    }
}
