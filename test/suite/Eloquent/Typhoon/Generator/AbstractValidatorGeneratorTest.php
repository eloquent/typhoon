<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Generator;

use Eloquent\Typhoon\TestCase\MultiGenerationTestCase;
use Icecave\Rasta\Renderer;

class AbstractValidatorGeneratorTest extends MultiGenerationTestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->_renderer = new Renderer;
        $this->_generator = new AbstractValidatorGenerator(
            $this->_renderer
        );
    }

    public function testConstructor()
    {
        $this->assertSame($this->_renderer, $this->_generator->renderer());
    }

    public function testConstructorDefaults()
    {
        $this->_generator = new AbstractValidatorGenerator;

        $this->assertInstanceOf(
            'Icecave\Rasta\Renderer',
            $this->_generator->renderer()
        );
    }

    public function testGenerate()
    {
        $expected = file_get_contents(
            __DIR__.
            '/../../../../src/Typhoon/Eloquent/Typhoon/TestFixture/ExampleValidator.php'
        );

        $this->assertSame($expected, $this->_generator->generate());
    }
}
