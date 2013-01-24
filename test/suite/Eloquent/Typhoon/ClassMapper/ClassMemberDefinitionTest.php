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
}
