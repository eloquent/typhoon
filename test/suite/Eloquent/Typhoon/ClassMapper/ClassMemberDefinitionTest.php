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

use Eloquent\Cosmos\ClassName;
use Eloquent\Typhoon\TestCase\MultiGenerationTestCase;
use Icecave\Pasta\AST\Type\AccessModifier;
use Phake;

class ClassMemberDefinitionTest extends MultiGenerationTestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->_definition = Phake::partialMock(
            __NAMESPACE__.'\ClassMemberDefinition',
            'foo',
            true,
            AccessModifier::PUBLIC_(),
            111,
            "bar\r\nbaz\rqux\ndoom"
        );
    }

    public function testConstruct()
    {
        $this->assertSame('foo', $this->_definition->name());
        $this->assertTrue($this->_definition->isStatic());
        $this->assertSame(AccessModifier::PUBLIC_(), $this->_definition->accessModifier());
        $this->assertSame(111, $this->_definition->lineNumber());
        $this->assertSame("bar\r\nbaz\rqux\ndoom", $this->_definition->source());
    }

    public function testEndLineNumber()
    {
        $this->assertSame(114, $this->_definition->endLineNumber());
    }

    public function testCompare()
    {
        $definitionA = Phake::partialMock(
            __NAMESPACE__.'\ClassMemberDefinition',
            'A',
            true,
            AccessModifier::PUBLIC_(),
            111,
            ''
        );
        $definitionB = Phake::partialMock(
            __NAMESPACE__.'\ClassMemberDefinition',
            'B',
            true,
            AccessModifier::PUBLIC_(),
            111,
            ''
        );
        $definitionC = Phake::partialMock(
            __NAMESPACE__.'\ClassMemberDefinition',
            'C',
            true,
            AccessModifier::PUBLIC_(),
            111,
            ''
        );
        $actual = array(
            $definitionB,
            $definitionA,
            $definitionC,
        );
        $expected = array(
            $definitionA,
            $definitionB,
            $definitionC,
        );
        usort($actual, __NAMESPACE__.'\MethodDefinition::compare');

        $this->assertSame($expected, $actual);
    }

    public function testCompareTuples()
    {
        $classDefinitionA = new ClassDefinition(ClassName::fromString('\A'));
        $classDefinitionB = new ClassDefinition(ClassName::fromString('\B'));
        $definitionA = Phake::partialMock(
            __NAMESPACE__.'\ClassMemberDefinition',
            'A',
            true,
            AccessModifier::PUBLIC_(),
            111,
            ''
        );
        $definitionB = Phake::partialMock(
            __NAMESPACE__.'\ClassMemberDefinition',
            'B',
            true,
            AccessModifier::PUBLIC_(),
            111,
            ''
        );
        $definitionC = Phake::partialMock(
            __NAMESPACE__.'\ClassMemberDefinition',
            'C',
            true,
            AccessModifier::PUBLIC_(),
            111,
            ''
        );
        $actual = array(
            array($classDefinitionA, $definitionB),
            array($classDefinitionB, $definitionB),
            array($classDefinitionB, $definitionA),
            array($classDefinitionA, $definitionA),
            array($classDefinitionA, $definitionC),
            array($classDefinitionB, $definitionC),
        );
        $expected = array(
            array($classDefinitionA, $definitionA),
            array($classDefinitionA, $definitionB),
            array($classDefinitionA, $definitionC),
            array($classDefinitionB, $definitionA),
            array($classDefinitionB, $definitionB),
            array($classDefinitionB, $definitionC),
        );
        usort($actual, __NAMESPACE__.'\MethodDefinition::compareTuples');

        $this->assertSame($expected, $actual);
    }
}
