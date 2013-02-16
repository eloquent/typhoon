<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Configuration\Exception;

use Eloquent\Typhoon\TestCase\MultiGenerationTestCase;
use Phake;

class ConfigurationReadExceptionTest extends MultiGenerationTestCase
{
    public function testException()
    {
        $previous = Phake::mock('Exception');
        $exception = new ConfigurationReadException(
            'foo',
            $previous
        );

        $this->assertSame(
            "Unable to read configuration from 'foo'.",
            $exception->getMessage()
        );
        $this->assertSame('foo', $exception->path());
        $this->assertSame(0, $exception->getCode());
        $this->assertSame($previous, $exception->getPrevious());
    }
}
