<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\ClassMapper;

use Eloquent\Cosmos\ClassName;
use Eloquent\Cosmos\ClassNameResolver;
use Eloquent\Typhoon\TestCase\MultiGenerationTestCase;
use Icecave\Pasta\AST\Type\AccessModifier;
use ReflectionClass;

class ClassDefinitionTest extends MultiGenerationTestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->_className = ClassName::fromString('doom\qux');
        $this->_usedClasses = array(
            array(
                ClassName::fromString('foo'),
                ClassName::fromString('bar'),
            ),
            array(
                ClassName::fromString('baz'),
            ),
        );
        $this->_methods = array(
            new MethodDefinition(
                $this->_className,
                'splat',
                false,
                false,
                AccessModifier::PUBLIC_(),
                111,
                'ping'
            ),
            new MethodDefinition(
                $this->_className,
                'pong',
                true,
                true,
                AccessModifier::PRIVATE_(),
                222,
                'pang'
            ),
        );
        $this->_properties = array(
            new PropertyDefinition(
                $this->_className,
                'peng',
                false,
                AccessModifier::PUBLIC_(),
                333,
                'pep'
            ),
            new PropertyDefinition(
                $this->_className,
                'pip',
                true,
                AccessModifier::PROTECTED_(),
                444,
                'pup'
            ),
        );
        $this->_definition = new ClassDefinition(
            $this->_className,
            $this->_usedClasses,
            $this->_methods,
            $this->_properties
        );

        $this->_expectedClassName = ClassName::fromString('\doom\qux');
        $this->_expectedNamespaceName = ClassName::fromString('\doom');
        $this->_expectedUsedClasses = array(
            array(
                ClassName::fromString('\foo'),
                ClassName::fromString('bar'),
            ),
            array(
                ClassName::fromString('\baz'),
                ClassName::fromString('baz'),
            ),
        );
    }

    public function testConstructor()
    {
        $this->assertEquals($this->_expectedClassName, $this->_definition->className());
        $this->assertEquals($this->_expectedUsedClasses, $this->_definition->usedClasses());
        $this->assertSame($this->_methods, $this->_definition->methods());
        $this->assertSame($this->_properties, $this->_definition->properties());
    }

    public function testConstructorDefaults()
    {
        $this->_definition = new ClassDefinition(
            $this->_className
        );

        $this->assertSame(array(), $this->_definition->usedClasses());
        $this->assertSame(array(), $this->_definition->methods());
        $this->assertSame(array(), $this->_definition->properties());
    }

    public function testHasMethod()
    {
        $this->assertTrue($this->_definition->hasMethod('splat'));
        $this->assertTrue($this->_definition->hasMethod('pong'));
        $this->assertFalse($this->_definition->hasMethod('foo'));
    }

    public function testMethod()
    {
        $this->assertSame($this->_methods[0], $this->_definition->method('splat'));
        $this->assertSame($this->_methods[1], $this->_definition->method('pong'));
    }

    public function testMethodFailureUndefined()
    {
        $this->setExpectedException(
            __NAMESPACE__.'\Exception\UndefinedMethodException'
        );
        $this->_definition->method('foo');
    }

    public function testHasProperty()
    {
        $this->assertTrue($this->_definition->hasProperty('peng'));
        $this->assertTrue($this->_definition->hasProperty('pip'));
        $this->assertFalse($this->_definition->hasProperty('foo'));
    }

    public function testProperty()
    {
        $this->assertSame($this->_properties[0], $this->_definition->property('peng'));
        $this->assertSame($this->_properties[1], $this->_definition->property('pip'));
    }

    public function testPropertyFailureUndefined()
    {
        $this->setExpectedException(
            __NAMESPACE__.'\Exception\UndefinedPropertyException'
        );
        $this->_definition->property('foo');
    }

    public function testClassNameResolver()
    {
        $expected = new ClassNameResolver(
            $this->_expectedNamespaceName,
            $this->_expectedUsedClasses
        );

        $this->assertEquals($expected, $this->_definition->classNameResolver());
    }

    public function testClassNameResolverWithoutNamespace()
    {
        $this->_definition = new ClassDefinition(
            ClassName::fromString('\qux'),
            $this->_usedClasses
        );
        $expected = new ClassNameResolver(
            null,
            $this->_expectedUsedClasses
        );

        $this->assertEquals($expected, $this->_definition->classNameResolver());
    }

    public function testCreateReflector()
    {
        $this->_definition = new ClassDefinition(
            ClassName::fromString(__CLASS__)
        );
        $expected = new ReflectionClass(__CLASS__);

        $this->assertEquals($expected, $this->_definition->createReflector());
    }
}
