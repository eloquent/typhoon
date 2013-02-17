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
use Eloquent\Typhax\Renderer\TypeRenderer;
use Eloquent\Typhax\Type\ArrayType;
use Eloquent\Typhax\Type\NullType;
use Eloquent\Typhax\Type\OrType;
use Eloquent\Typhoon\TestCase\MultiGenerationTestCase;
use Phake;

class DocumentedParameterTypeMismatchExceptionTest extends MultiGenerationTestCase
{
    public function testException()
    {
        $className = ClassName::fromString('\baz');
        $documentedType = new ArrayType;
        $nativeType = new OrType(array(
            new ArrayType,
            new NullType,
        ));
        $previous = Phake::mock('Exception');
        $typeRenderer = new TypeRenderer;
        $exception = new DocumentedParameterTypeMismatchException(
            $className,
            'foo',
            'bar',
            $documentedType,
            $nativeType,
            $previous,
            $typeRenderer
        );

        $this->assertSame(
            "Documented type 'array' is not correct for defined type 'array|null' for parameter \$bar in method \baz::foo().",
            $exception->getMessage()
        );
        $this->assertSame($className, $exception->className());
        $this->assertSame('foo', $exception->functionName());
        $this->assertSame('bar', $exception->parameterName());
        $this->assertSame($documentedType, $exception->documentedType());
        $this->assertSame($nativeType, $exception->nativeType());
        $this->assertSame($typeRenderer, $exception->typeRenderer());
        $this->assertSame(0, $exception->getCode());
        $this->assertSame($previous, $exception->getPrevious());
    }

    public function testExceptionWithoutClassName()
    {
        $documentedType = new ArrayType;
        $nativeType = new OrType(array(
            new ArrayType,
            new NullType,
        ));
        $exception = new DocumentedParameterTypeMismatchException(
            null,
            'foo',
            'bar',
            $documentedType,
            $nativeType
        );

        $this->assertSame(
            "Documented type 'array' is not correct for defined type 'array|null' for parameter \$bar in function foo().",
            $exception->getMessage()
        );
        $this->assertNull($exception->className());
    }

    public function testConstructorDefaults()
    {
        $documentedType = new ArrayType;
        $nativeType = new OrType(array(
            new ArrayType,
            new NullType,
        ));
        $exception = new DocumentedParameterTypeMismatchException(
            null,
            'foo',
            'bar',
            $documentedType,
            $nativeType
        );

        $this->assertInstanceOf('Eloquent\Typhax\Renderer\TypeRenderer', $exception->typeRenderer());
        $this->assertSame(0, $exception->getCode());
        $this->assertNull($exception->getPrevious());
    }
}