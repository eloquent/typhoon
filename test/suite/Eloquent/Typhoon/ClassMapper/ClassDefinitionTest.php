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
use Eloquent\Typhoon\TestCase\MultiGenerationTestCase;
use Icecave\Pasta\AST\Type\AccessModifier;
use Phake;

class ClassDefinitionTest extends MultiGenerationTestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->_usedClasses = array(
            'foo' => 'bar',
            'baz' => null,
        );
        $this->_methods = array(
            new MethodDefinition(
                'splat',
                false,
                AccessModifier::PUBLIC_(),
                111,
                'ping'
            ),
            new MethodDefinition(
                'pong',
                true,
                AccessModifier::PRIVATE_(),
                222,
                'pang'
            ),
        );
        $this->_properties = array(
            new PropertyDefinition(
                'peng',
                false,
                AccessModifier::PUBLIC_(),
                333,
                'pep'
            ),
            new PropertyDefinition(
                'pip',
                true,
                AccessModifier::PROTECTED_(),
                444,
                'pup'
            ),
        );
        $this->_definition = new ClassDefinition(
            'qux',
            'doom',
            $this->_usedClasses,
            $this->_methods,
            $this->_properties
        );
    }

    public function testConstructor()
    {
        $this->assertSame('qux', $this->_definition->className());
        $this->assertSame('doom', $this->_definition->namespaceName());
        $this->assertSame($this->_usedClasses, $this->_definition->usedClasses());
        $this->assertSame($this->_methods, $this->_definition->methods());
        $this->assertSame($this->_properties, $this->_definition->properties());
    }

    public function testConstructorDefaults()
    {
        $this->_definition = new ClassDefinition(
            'foo'
        );

        $this->assertNull($this->_definition->namespaceName());
        $this->assertSame(array(), $this->_definition->usedClasses());
        $this->assertSame(array(), $this->_definition->methods());
        $this->assertSame(array(), $this->_definition->properties());
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
