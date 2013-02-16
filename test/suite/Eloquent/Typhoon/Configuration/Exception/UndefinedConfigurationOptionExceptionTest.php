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

class UndefinedConfigurationOptionExceptionTest extends MultiGenerationTestCase
{
    public function testException()
    {
        $previous = Phake::mock('Exception');
        $exception = new UndefinedConfigurationOptionException(
            'foo',
            $previous
        );

        $this->assertSame(
            "Undefined configuration option 'foo'.",
            $exception->getMessage()
        );
        $this->assertSame('foo', $exception->optionName());
        $this->assertSame(0, $exception->getCode());
        $this->assertSame($previous, $exception->getPrevious());
    }
}
