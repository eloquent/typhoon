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

class DocumentedParameterNameMismatchExceptionTest extends MultiGenerationTestCase
{
    public function testException()
    {
        $className = ClassName::fromString('\qux');
        $previous = Phake::mock('Exception');
        $exception = new DocumentedParameterNameMismatchException(
            $className,
            'foo',
            'bar',
            'baz',
            $previous
        );

        $this->assertSame(
            'Documented parameter name $bar does not match defined parameter name $baz in method \qux::foo().',
            $exception->getMessage()
        );
        $this->assertSame($className, $exception->className());
        $this->assertSame('foo', $exception->functionName());
        $this->assertSame('bar', $exception->documentedParameterName());
        $this->assertSame('baz', $exception->nativeParameterName());
        $this->assertSame(0, $exception->getCode());
        $this->assertSame($previous, $exception->getPrevious());
    }

    public function testExceptionWithoutClassName()
    {
        $exception = new DocumentedParameterNameMismatchException(
            null,
            'foo',
            'bar',
            'baz'
        );

        $this->assertSame(
            'Documented parameter name $bar does not match defined parameter name $baz in function foo().',
            $exception->getMessage()
        );
        $this->assertNull($exception->className());
    }
}
