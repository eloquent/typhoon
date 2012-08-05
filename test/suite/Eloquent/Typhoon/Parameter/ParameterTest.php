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
use Phake;
use PHPUnit_Framework_TestCase;

class ParameterTest extends PHPUnit_Framework_TestCase
{
    public function testParameter()
    {
        $type = new StringType;
        $parameter = new Parameter(
            'foo',
            $type,
            true,
            'bar'
        );

        $this->assertSame('foo', $parameter->name());
        $this->assertSame($type, $parameter->type());
        $this->assertTrue($parameter->isOptional());
        $this->assertSame('bar', $parameter->description());
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
        $this->assertFalse($parameter->isOptional());
        $this->assertNull($parameter->description());
    }
}
