<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Resolver;

use Eloquent\Cosmos\ClassName;
use Eloquent\Cosmos\ClassNameResolver;
use Eloquent\Typhax\Resolver\ObjectTypeClassNameResolver;
use Eloquent\Typhax\Type\ObjectType;
use Eloquent\Typhoon\Parameter\Parameter;
use Eloquent\Typhoon\Parameter\ParameterList;
use Eloquent\Typhoon\TestCase\MultiGenerationTestCase;

class ParameterListClassNameResolverTest extends MultiGenerationTestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->_classNameResolver = new ClassNameResolver(
            ClassName::fromString('\Foo\Bar\Baz'),
            array(
                array(
                    ClassName::fromString('\Qux\Doom\Splat'),
                    ClassName::fromString('Pip'),
                ),
            )
        );
        $this->_typeResolver = new ObjectTypeClassNameResolver(
            $this->_classNameResolver
        );
        $this->_resolver = new ParameterListClassNameResolver(
            $this->_typeResolver
        );
    }

    public function testResolver()
    {
        $list = new ParameterList(array(
            new Parameter(
                'foo',
                new ObjectType(ClassName::fromString('Spam')),
                'Foo description',
                false,
                true
            ),
            new Parameter(
                'bar',
                new ObjectType(ClassName::fromString('Pip')),
                'Bar description',
                true,
                false
            ),
        ));
        $expected = new ParameterList(array(
            new Parameter(
                'foo',
                new ObjectType(ClassName::fromString('\Foo\Bar\Baz\Spam')),
                'Foo description',
                false,
                true
            ),
            new Parameter(
                'bar',
                new ObjectType(ClassName::fromString('\Qux\Doom\Splat')),
                'Bar description',
                true,
                false
            ),
        ));

        $this->assertEquals($expected, $list->accept($this->_resolver));
    }

    public function testResolverVariableLength()
    {
        $list = new ParameterList(
            array(
                new Parameter(
                    'foo',
                    new ObjectType(ClassName::fromString('Spam')),
                    'Foo description',
                    false,
                    true
                ),
                new Parameter(
                    'bar',
                    new ObjectType(ClassName::fromString('Pip')),
                    'Bar description',
                    true,
                    false
                ),
            ),
            true
        );
        $expected = new ParameterList(
            array(
                new Parameter(
                    'foo',
                    new ObjectType(ClassName::fromString('\Foo\Bar\Baz\Spam')),
                    'Foo description',
                    false,
                    true
                ),
                new Parameter(
                    'bar',
                    new ObjectType(ClassName::fromString('\Qux\Doom\Splat')),
                    'Bar description',
                    true,
                    false
                ),
            ),
            true
        );

        $this->assertEquals($expected, $list->accept($this->_resolver));
    }
}
