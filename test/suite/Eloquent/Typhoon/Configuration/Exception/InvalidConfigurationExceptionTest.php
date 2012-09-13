<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Configuration\Exception;

use Eloquent\Typhoon\TestCase\MultiGenerationTestCase;
use Phake;

class InvalidConfigurationExceptionTest extends MultiGenerationTestCase
{
    public function testException()
    {
        $previous = Phake::mock('Exception');
        $exception = new InvalidConfigurationException(
            'foo',
            'bar',
            $previous
        );

        $this->assertSame(
            "Invalid configuration. 'foo' bar.",
            $exception->getMessage()
        );
        $this->assertSame('foo', $exception->propertyName());
        $this->assertSame('bar', $exception->validationErrorMessage());
        $this->assertSame(0, $exception->getCode());
        $this->assertSame($previous, $exception->getPrevious());
    }
}
