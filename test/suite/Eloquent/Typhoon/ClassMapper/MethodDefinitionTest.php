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

class MethodDefinitionTest extends MultiGenerationTestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->_definition = new MethodDefinition(
            'foo',
            true,
            111,
            'bar'
        );
    }

    public function testConstruct()
    {
        $this->assertSame('foo', $this->_definition->methodName());
        $this->assertTrue($this->_definition->isStatic());
        $this->assertSame(111, $this->_definition->lineNumber());
        $this->assertSame('bar', $this->_definition->source());
    }
}
