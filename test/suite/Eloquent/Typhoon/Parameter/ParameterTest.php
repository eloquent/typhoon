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
use PHPUnit_Framework_TestCase;

class ParameterTest extends PHPUnit_Framework_TestCase
{
    public function testParameter()
    {
        $type = new StringType;
        $parameter = new Parameter(
            $type,
            'foo',
            'bar'
        );

        $this->assertSame($type, $parameter->type());
        $this->assertSame('foo', $parameter->name());
        $this->assertSame('bar', $parameter->description());
    }

    public function testParameterOptionalParameters()
    {
        $type = new StringType;
        $parameter = new Parameter(
            $type
        );

        $this->assertSame($type, $parameter->type());
        $this->assertNull($parameter->name());
        $this->assertNull($parameter->description());
    }
}
