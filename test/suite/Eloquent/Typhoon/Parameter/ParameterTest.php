<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Parameter;

use Eloquent\Typhax\Type\StringType;
use Eloquent\Typhoon\TestCase\MultiGenerationTestCase;
use Phake;

class ParameterTest extends MultiGenerationTestCase
{
    public function testParameter()
    {
        $type = new StringType;
        $parameter = new Parameter(
            'foo',
            $type,
            'bar',
            true,
            true
        );

        $this->assertSame('foo', $parameter->name());
        $this->assertSame($type, $parameter->type());
        $this->assertSame('bar', $parameter->description());
        $this->assertTrue($parameter->isOptional());
        $this->assertTrue($parameter->isByReference());
    }

    public function testParameterOptionalParameters()
    {
        $type = new StringType;
        $parameter = new Parameter(
            'foo',
            $type
        );

        $this->assertSame('foo', $parameter->name());
        $this->assertSame($type, $parameter->type());
        $this->assertNull($parameter->description());
        $this->assertFalse($parameter->isOptional());
        $this->assertFalse($parameter->isByReference());
    }
}
