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

use Eloquent\Typhax\Type\MixedType;
use Eloquent\Typhoon\TestCase\MultiGenerationTestCase;

class ParameterListTest extends MultiGenerationTestCase
{
    public function testParameterList()
    {
        $parameterFoo = new Parameter('foo', new MixedType);
        $parameterBar = new Parameter('bar', new MixedType);
        $parameters = array(
            $parameterFoo,
            $parameterBar,
        );
        $list = new ParameterList(
            $parameters,
            true
        );

        $this->assertSame($parameters, $list->parameters());
        $this->assertTrue($list->isVariableLength());
    }

    public function testParameterListOptionalParameters()
    {
        $list = new ParameterList;

        $this->assertSame(array(), $list->parameters());
        $this->assertFalse($list->isVariableLength());
    }

    public function testParameterByName()
    {
        $parameterFoo = new Parameter('foo', new MixedType);
        $parameterBar = new Parameter('bar', new MixedType);
        $parameters = array(
            $parameterFoo,
            $parameterBar,
        );
        $list = new ParameterList(
            $parameters
        );

        $this->assertSame($parameterFoo, $list->parameterByName('foo'));
        $this->assertSame($parameterBar, $list->parameterByName('bar'));
        $this->assertNull($list->parameterByName('baz'));
    }

    public function testRequiredParameters()
    {
        $parameterFoo = new Parameter('foo', new MixedType);
        $parameterBar = new Parameter('bar', new MixedType, null, true);
        $parameterBaz = new Parameter('baz', new MixedType);
        $parameterQux = new Parameter('qux', new MixedType, null, true);
        $list = new ParameterList(array(
            $parameterFoo,
            $parameterBar,
            $parameterBaz,
            $parameterQux,
        ));
        $expected = array(
            $parameterFoo,
            $parameterBar,
            $parameterBaz,
        );

        $this->assertSame($expected, $list->requiredParameters());
    }
}
