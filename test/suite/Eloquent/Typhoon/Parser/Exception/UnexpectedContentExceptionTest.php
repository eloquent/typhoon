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

use Eloquent\Typhoon\TestCase\MultiGenerationTestCase;
use Phake;

class UnexpectedContentExceptionTest extends MultiGenerationTestCase
{
    public function testException()
    {
        $previous = Phake::mock('Exception');
        $exception = new UnexpectedContentException(
            'foo',
            111,
            $previous
        );

        $this->assertSame("Unexpected content at position 111. Expected 'foo'.", $exception->getMessage());
        $this->assertSame('foo', $exception->expected());
        $this->assertSame(111, $exception->position());
        $this->assertSame(0, $exception->getCode());
        $this->assertSame($previous, $exception->getPrevious());
    }
}
