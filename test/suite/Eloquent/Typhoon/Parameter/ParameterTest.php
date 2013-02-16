<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2013 Erin Millard
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

    public function testAccept()
    {
        $parameter = new Parameter('foo', new StringType);

        $visitor = Phake::mock(__NAMESPACE__ . '\\Visitor');

        Phake::when($visitor)
            ->visitParameter($parameter)
            ->thenReturn('<visitor result>');

        $result = $parameter->accept($visitor);

        $this->assertSame('<visitor result>', $result);
    }
}
