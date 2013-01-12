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

use Eloquent\Typhoon\TestCase\MultiGenerationTestCase;
use Icecave\Pasta\AST\Type\AccessModifier;

class PropertyDefinitionTest extends MultiGenerationTestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->_definition = new PropertyDefinition(
            'foo',
            true,
            AccessModifier::PUBLIC_(),
            111
        );
    }

    public function testConstruct()
    {
        $this->assertSame('foo', $this->_definition->propertyName());
        $this->assertTrue($this->_definition->isStatic());
        $this->assertSame(AccessModifier::PUBLIC_(), $this->_definition->accessModifier());
        $this->assertSame(111, $this->_definition->lineNumber());
    }
}
