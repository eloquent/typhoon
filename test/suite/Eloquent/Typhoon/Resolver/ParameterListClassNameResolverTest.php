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

use Eloquent\Cosmos\ClassNameResolver;
use Eloquent\Typhax\Resolver\ObjectTypeClassNameResolver;
use Eloquent\Typhax\Type\ObjectType;
use Eloquent\Typhoon\Parameter\Parameter;
use Eloquent\Typhoon\Parameter\ParameterList;
use PHPUnit_Framework_TestCase;

class ParameterListClassNameResolverTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->_classNameResolver = new ClassNameResolver(
            'Foo\Bar\Baz',
            array(
                'Qux\Doom\Splat' => 'Pip',
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
                new ObjectType('Spam')
            ),
            new Parameter(
                'bar',
                new ObjectType('Pip')
            ),
        ));
        $expected = new ParameterList(array(
            new Parameter(
                'foo',
                new ObjectType('Foo\Bar\Baz\Spam')
            ),
            new Parameter(
                'bar',
                new ObjectType('Qux\Doom\Splat')
            ),
        ));

        $this->assertEquals($expected, $list->accept($this->_resolver));
    }
}
