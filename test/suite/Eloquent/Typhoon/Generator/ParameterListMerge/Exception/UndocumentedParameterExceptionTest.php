<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Generator\ParameterListMerge\Exception;

use Eloquent\Typhoon\TestCase\MultiGenerationTestCase;
use Phake;

class UndocumentedParameterExceptionTest extends MultiGenerationTestCase
{
    public function testException()
    {
        $previous = Phake::mock('Exception');
        $exception = new UndocumentedParameterException(
            'foo',
            'bar',
            $previous
        );

        $this->assertSame(
            "Parameter 'bar' is undocumented in 'foo'.",
            $exception->getMessage()
        );
        $this->assertSame('foo', $exception->functionName());
        $this->assertSame('bar', $exception->parameterName());
        $this->assertSame(0, $exception->getCode());
        $this->assertSame($previous, $exception->getPrevious());
    }
}
