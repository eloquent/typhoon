<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Parser\Exception;

use Phake;
use PHPUnit_Framework_TestCase;

class ParseExceptionTest extends PHPUnit_Framework_TestCase
{
    public function testException()
    {
        $previous = Phake::mock('Exception');
        $exception = Phake::partialMock(
            __NAMESPACE__.'\ParseException',
            'foo',
            111,
            $previous
        );

        $this->assertSame('foo', $exception->getMessage());
        $this->assertSame(111, $exception->position());
        $this->assertSame($previous, $exception->getPrevious());
    }
}
