<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Parser\Exception;

use Eloquent\Cosmos\ClassName;
use Eloquent\Typhoon\TestCase\MultiGenerationTestCase;
use Phake;

class InvalidFunctionDocumentationExceptionTest extends MultiGenerationTestCase
{
    public function testException()
    {
        $previous = Phake::mock('Exception');
        $className = ClassName::fromString('\foo');
        $exception = new InvalidFunctionDocumentationException(
            $className,
            'bar',
            $previous
        );

        $this->assertSame('Invalid param tags found in the documentation for method \foo::bar().', $exception->getMessage());
        $this->assertSame($className, $exception->className());
        $this->assertSame('bar', $exception->functionName());
        $this->assertSame(0, $exception->getCode());
        $this->assertSame($previous, $exception->getPrevious());
    }

    public function testExceptionWithoutClassName()
    {
        $previous = Phake::mock('Exception');
        $exception = new InvalidFunctionDocumentationException(
            null,
            'bar',
            $previous
        );

        $this->assertSame('Invalid param tags found in the documentation for function bar().', $exception->getMessage());
        $this->assertNull($exception->className());
        $this->assertSame('bar', $exception->functionName());
        $this->assertSame(0, $exception->getCode());
        $this->assertSame($previous, $exception->getPrevious());
    }
}
