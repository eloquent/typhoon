<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Resolver;

use Eloquent\Typhax\Type\StringType;
use Eloquent\Typhoon\Parameter\Parameter;
use Eloquent\Typhoon\Parameter\ParameterList;
use PHPUnit_Framework_TestCase;
use ReflectionMethod;

class ParameterListReflectionResolverTest extends PHPUnit_Framework_TestCase
{
    protected function resolverFixture($method)
    {
        return new ParameterListReflectionResolver(
            new ReflectionMethod($this, $method)
        );
    }

    public function testResolverOptional()
    {
        $resolver = $this->resolverFixture('methodOptional');

        $list = new ParameterList(array(
            new Parameter(
                'foo',
                new StringType,
                false,
                'Foo description'
            ),
            new Parameter(
                'bar',
                new StringType,
                false,
                'Bar description'
            ),
        ));
        $expected = new ParameterList(array(
            new Parameter(
                'foo',
                new StringType,
                false,
                'Foo description'
            ),
            new Parameter(
                'bar',
                new StringType,
                true,
                'Bar description'
            ),
        ));

        $this->assertEquals($expected, $list->accept($resolver));
    }

    protected function methodOptional($foo, $bar = 'baz')
    {
    }

    public function testResolverEmpty()
    {
        $resolver = $this->resolverFixture('methodEmpty');

        $list = new ParameterList(array(
            new Parameter(
                'foo',
                new StringType,
                false,
                'Foo description'
            ),
            new Parameter(
                'bar',
                new StringType,
                false,
                'Bar description'
            ),
        ));
        $expected = new ParameterList(array(
            new Parameter(
                'foo',
                new StringType,
                false,
                'Foo description'
            ),
            new Parameter(
                'bar',
                new StringType,
                false,
                'Bar description'
            ),
        ));

        $this->assertEquals($expected, $list->accept($resolver));
    }

    protected function methodEmpty()
    {
    }

    public function testResolverVariableLength()
    {
        $resolver = $this->resolverFixture('methodEmpty');

        $list = new ParameterList(
            array(
                new Parameter(
                    'foo',
                    new StringType,
                    false,
                    'Foo description'
                ),
                new Parameter(
                    'bar',
                    new StringType,
                    false,
                    'Bar description'
                ),
            ),
            true
        );
        $expected = new ParameterList(
            array(
                new Parameter(
                    'foo',
                    new StringType,
                    false,
                    'Foo description'
                ),
                new Parameter(
                    'bar',
                    new StringType,
                    false,
                    'Bar description'
                ),
            ),
            true
        );

        $this->assertEquals($expected, $list->accept($resolver));
    }
}
