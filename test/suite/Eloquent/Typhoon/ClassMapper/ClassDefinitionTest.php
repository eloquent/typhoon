<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\ClassMapper;

use Eloquent\Cosmos\ClassNameResolver;
use Phake;
use PHPUnit_Framework_TestCase;

class ClassDefinitionTest extends PHPUnit_Framework_TestCase
{
    public function testDefinition()
    {
        $usedClasses = array(
            'foo' => 'bar',
            'baz' => null,
        );
        $definition = new ClassDefinition(
            'qux',
            'doom',
            $usedClasses
        );

        $this->assertSame('qux', $definition->className());
        $this->assertSame('doom', $definition->namespaceName());
        $this->assertSame($usedClasses, $definition->usedClasses());
    }

    public function testDefinitionConstructorDefaults()
    {
        $definition = new ClassDefinition('foo');

        $this->assertSame('foo', $definition->className());
        $this->assertNull($definition->namespaceName());
        $this->assertSame(array(), $definition->usedClasses());
    }

    public function testCanonicalClassName()
    {
        $resolver = Phake::mock('Eloquent\Cosmos\ClassNameResolver');
        Phake::when($resolver)->resolve(Phake::anyParameters())->thenReturn('foo');
        $definition = Phake::partialMock(__NAMESPACE__.'\ClassDefinition', 'bar');
        Phake::when($definition)->classNameResolver()->thenReturn($resolver);

        $this->assertSame('foo', $definition->canonicalClassName());
        Phake::verify($resolver)->resolve('bar');
    }

    public function testClassNameResolver()
    {
        $usedClasses = array(
            'foo' => 'bar',
            'baz' => null,
        );
        $definition = new ClassDefinition(
            'qux',
            'doom',
            $usedClasses
        );
        $expected = new ClassNameResolver(
            'doom',
            $usedClasses
        );

        $this->assertEquals($expected, $definition->classNameResolver());
    }
}
