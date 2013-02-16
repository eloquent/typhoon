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

class DocumentedParameterByReferenceMismatchExceptionTest extends MultiGenerationTestCase
{
    public function testExceptionDocumentedByReference()
    {
        $className = ClassName::fromString('\baz');
        $previous = Phake::mock('Exception');
        $exception = new DocumentedParameterByReferenceMismatchException(
            $className,
            'foo',
            'bar',
            true,
            false,
            $previous
        );

        $this->assertSame(
            'Parameter $bar is documented as by-reference but defined as by-value in method \baz::foo().',
            $exception->getMessage()
        );
        $this->assertSame($className, $exception->className());
        $this->assertSame('foo', $exception->functionName());
        $this->assertSame('bar', $exception->parameterName());
        $this->assertTrue($exception->documentedIsByReference());
        $this->assertFalse($exception->nativeIsByReference());
        $this->assertSame(0, $exception->getCode());
        $this->assertSame($previous, $exception->getPrevious());
    }

    public function testExceptionDefinedByReference()
    {
        $className = ClassName::fromString('\baz');
        $exception = new DocumentedParameterByReferenceMismatchException(
            $className,
            'foo',
            'bar',
            false,
            true
        );

        $this->assertSame(
            'Parameter $bar is documented as by-value but defined as by-reference in method \baz::foo().',
            $exception->getMessage()
        );
    }

    public function testExceptionWithoutClassName()
    {
        $exception = new DocumentedParameterByReferenceMismatchException(
            null,
            'foo',
            'bar',
            true,
            false
        );

        $this->assertSame(
            'Parameter $bar is documented as by-reference but defined as by-value in function foo().',
            $exception->getMessage()
        );
        $this->assertNull($exception->className());
    }
}
