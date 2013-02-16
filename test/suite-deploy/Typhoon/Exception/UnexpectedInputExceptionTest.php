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

class UnexpectedInputExceptionTest extends PHPUnit_Framework_TestCase
{
    public function testException()
    {
        $previous = Phake::mock('Exception');
        $exception = Phake::partialMock(
            __NAMESPACE__.'\UnexpectedInputException',
            'foo',
            $previous
        );

        $this->assertSame('foo', $exception->getMessage());
        $this->assertSame(0, $exception->getCode());
        $this->assertSame($previous, $exception->getPrevious());
    }
}
