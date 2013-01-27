<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Generator\ParameterListMerge\Exception;

use Eloquent\Typhoon\TestCase\MultiGenerationTestCase;
use Phake;

class DocumentedParameterByReferenceMismatchExceptionTest extends MultiGenerationTestCase
{
    public function testExceptionDocumentedByReference()
    {
        $previous = Phake::mock('Exception');
        $exception = new DocumentedParameterByReferenceMismatchException(
            'foo',
            'bar',
            true,
            false,
            $previous
        );

        $this->assertSame(
            "Parameter 'bar' is documented as by-reference but defined as by-value in 'foo'.",
            $exception->getMessage()
        );
        $this->assertSame('foo', $exception->functionName());
        $this->assertSame('bar', $exception->parameterName());
        $this->assertTrue($exception->documentedIsByReference());
        $this->assertFalse($exception->nativeIsByReference());
        $this->assertSame(0, $exception->getCode());
        $this->assertSame($previous, $exception->getPrevious());
    }

    public function testExceptionDefinedByReference()
    {
        $previous = Phake::mock('Exception');
        $exception = new DocumentedParameterByReferenceMismatchException(
            'foo',
            'bar',
            false,
            true,
            $previous
        );

        $this->assertSame(
            "Parameter 'bar' is documented as by-value but defined as by-reference in 'foo'.",
            $exception->getMessage()
        );
        $this->assertSame('foo', $exception->functionName());
        $this->assertSame('bar', $exception->parameterName());
        $this->assertFalse($exception->documentedIsByReference());
        $this->assertTrue($exception->nativeIsByReference());
        $this->assertSame(0, $exception->getCode());
        $this->assertSame($previous, $exception->getPrevious());
    }
}
