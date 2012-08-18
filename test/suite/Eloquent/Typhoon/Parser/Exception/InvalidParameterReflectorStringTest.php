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

class InvalidParameterReflectorStringTest extends PHPUnit_Framework_TestCase
{
    public function testException()
    {
        $previous = Phake::mock('Exception');
        $exception = new InvalidParameterReflectorString(
            'foo',
            $previous
        );

        $this->assertSame("Unable to parse ReflectionParameter string 'foo'.", $exception->getMessage());
        $this->assertSame('foo', $exception->parameterString());
        $this->assertSame(0, $exception->getCode());
        $this->assertSame($previous, $exception->getPrevious());
    }
}
