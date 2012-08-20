<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Generator\Exception;

use Eloquent\Typhax\Renderer\TypeRenderer;
use Eloquent\Typhax\Type\ArrayType;
use Eloquent\Typhax\Type\NullType;
use Eloquent\Typhax\Type\OrType;
use Phake;
use PHPUnit_Framework_TestCase;

class DocumentedParameterTypeMismatchExceptionTest extends PHPUnit_Framework_TestCase
{
    public function testException()
    {
        $documentedType = new ArrayType;
        $nativeType = new OrType(array(
            new ArrayType,
            new NullType,
        ));
        $previous = Phake::mock('Exception');
        $typeRenderer = new TypeRenderer;
        $exception = new DocumentedParameterTypeMismatchException(
            'foo',
            $documentedType,
            $nativeType,
            $previous,
            $typeRenderer
        );

        $this->assertSame(
            "Documented parameter type 'array' does not match defined parameter type 'array|null' in 'foo'.",
            $exception->getMessage()
        );
        $this->assertSame('foo', $exception->functionName());
        $this->assertSame($documentedType, $exception->documentedType());
        $this->assertSame($nativeType, $exception->nativeType());
        $this->assertSame($typeRenderer, $exception->typeRenderer());
        $this->assertSame(0, $exception->getCode());
        $this->assertSame($previous, $exception->getPrevious());
    }

    public function testConstructorDefaults()
    {
        $documentedType = new ArrayType;
        $nativeType = new OrType(array(
            new ArrayType,
            new NullType,
        ));
        $exception = new DocumentedParameterTypeMismatchException(
            'foo',
            $documentedType,
            $nativeType
        );

        $this->assertSame(
            "Documented parameter type 'array' does not match defined parameter type 'array|null' in 'foo'.",
            $exception->getMessage()
        );
        $this->assertSame('foo', $exception->functionName());
        $this->assertSame($documentedType, $exception->documentedType());
        $this->assertSame($nativeType, $exception->nativeType());
        $this->assertInstanceOf('Eloquent\Typhax\Renderer\TypeRenderer', $exception->typeRenderer());
        $this->assertSame(0, $exception->getCode());
        $this->assertNull($exception->getPrevious());
    }
}
