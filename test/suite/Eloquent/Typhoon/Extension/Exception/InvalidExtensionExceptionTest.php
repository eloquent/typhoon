<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Extension\Exception;

use Eloquent\Typhoon\TestCase\MultiGenerationTestCase;
use Phake;

class InvalidExtensionExceptionTest extends MultiGenerationTestCase
{
    public function testException()
    {
        $previous = Phake::mock('Exception');
        $exception = new InvalidExtensionException(
            'foo',
            $previous
        );

        $this->assertSame(
            "The extension type 'foo' does not exist, or does not implement ExtensionInterface.",
            $exception->getMessage()
        );
        $this->assertSame('foo', $exception->className());
        $this->assertSame(0, $exception->getCode());
        $this->assertSame($previous, $exception->getPrevious());
    }
}
