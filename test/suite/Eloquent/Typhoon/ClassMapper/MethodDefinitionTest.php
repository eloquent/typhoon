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
use Eloquent\Typhoon\TestCase\MultiGenerationTestCase;
use Icecave\Pasta\AST\Type\AccessModifier;
use ReflectionMethod;

/**
 * @covers \Eloquent\Typhoon\ClassMapper\MethodDefinition
 * @covers \Eloquent\Typhoon\ClassMapper\ClassMemberDefinition
 */
class MethodDefinitionTest extends MultiGenerationTestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->_className = ClassName::fromString('\splat');
        $this->_definition = new MethodDefinition(
            $this->_className,
            'foo',
            true,
            true,
            AccessModifier::PUBLIC_(),
            111,
            "bar\r\nbaz\rqux\ndoom"
        );
    }

    public function testConstruct()
    {
        $this->assertSame($this->_className, $this->_definition->className());
        $this->assertSame('foo', $this->_definition->name());
        $this->assertTrue($this->_definition->isStatic());
        $this->assertTrue($this->_definition->isAbstract());
        $this->assertSame(AccessModifier::PUBLIC_(), $this->_definition->accessModifier());
        $this->assertSame(111, $this->_definition->lineNumber());
        $this->assertSame("bar\r\nbaz\rqux\ndoom", $this->_definition->source());
    }

    public function testEndLineNumber()
    {
        $this->assertSame(114, $this->_definition->endLineNumber());
    }

    public function testCreateReflector()
    {
        $this->_className = ClassName::fromString(__CLASS__);
        $this->_definition = new MethodDefinition(
            $this->_className,
            'testCreateReflector',
            false,
            false,
            AccessModifier::PUBLIC_(),
            111,
            "foo"
        );
        $expected = new ReflectionMethod(__CLASS__, 'testCreateReflector');

        $this->assertEquals($expected, $this->_definition->createReflector());
    }
}
