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

class InvalidFunctionDocumentationExceptionTest extends MultiGenerationTestCase
{
    public function testException()
    {
        $previous = Phake::mock('Exception');
        $exception = new InvalidFunctionDocumentationException(
            'foo',
            $previous
        );

        $this->assertSame("Invalid param tags found in the documentation for foo().", $exception->getMessage());
        $this->assertSame('foo', $exception->functionName());
        $this->assertSame(0, $exception->getCode());
        $this->assertSame($previous, $exception->getPrevious());
    }
}