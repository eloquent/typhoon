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

use Phake;
use PHPUnit_Framework_TestCase;

class ParameterListTest extends PHPUnit_Framework_TestCase
{
    public function testParameterList()
    {
        $parameterFoo = Phake::mock(__NAMESPACE__.'\Parameter');
        $parameterBar = Phake::mock(__NAMESPACE__.'\Parameter');
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
        $parameterFoo = Phake::mock(__NAMESPACE__.'\Parameter');
        Phake::when($parameterFoo)->name()->thenReturn('foo');
        $parameterBar = Phake::mock(__NAMESPACE__.'\Parameter');
        Phake::when($parameterBar)->name()->thenReturn('bar');
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
}
