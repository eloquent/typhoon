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

use Eloquent\Cosmos\ClassName;
use Eloquent\Typhoon\TestCase\MultiGenerationTestCase;
use Phake;

class DocumentedParameterUndefinedExceptionTest extends MultiGenerationTestCase
{
    public function testException()
    {
        $className = ClassName::fromString('\baz');
        $previous = Phake::mock('Exception');
        $exception = new DocumentedParameterUndefinedException(
            $className,
            'foo',
            'bar',
            $previous
        );

        $this->assertSame(
            'Documented parameter $bar not defined in method \baz::foo().',
            $exception->getMessage()
        );
        $this->assertSame($className, $exception->className());
        $this->assertSame('foo', $exception->functionName());
        $this->assertSame('bar', $exception->parameterName());
        $this->assertSame(0, $exception->getCode());
        $this->assertSame($previous, $exception->getPrevious());
    }

    public function testExceptionWithoutClassName()
    {
        $exception = new DocumentedParameterUndefinedException(
            null,
            'foo',
            'bar'
        );

        $this->assertSame(
            'Documented parameter $bar not defined in function foo().',
            $exception->getMessage()
        );
        $this->assertNull($exception->className());
    }
}
