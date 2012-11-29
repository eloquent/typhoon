<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Generator\ExceptionGenerator;

use Eloquent\Typhoon\Configuration\RuntimeConfiguration;
use Eloquent\Typhoon\TestCase\MultiGenerationTestCase;
use Icecave\Rasta\Renderer;

class UnexpectedInputExceptionGeneratorTest extends MultiGenerationTestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->_renderer = new Renderer;
        $this->_generator = new UnexpectedInputExceptionGenerator(
            $this->_renderer
        );
    }

    public function testConstructor()
    {
        $this->assertSame($this->_renderer, $this->_generator->renderer());
    }

    public function testConstructorDefaults()
    {
        $this->_generator = new UnexpectedInputExceptionGenerator;

        $this->assertInstanceOf(
            'Icecave\Rasta\Renderer',
            $this->_generator->renderer()
        );
    }

    public function testGenerate()
    {
        $configuration = new RuntimeConfiguration;
        $expected = file_get_contents(
            __DIR__.
            '/../../../../../src/Typhoon/Eloquent/Typhoon/TestFixture/Exception/ExampleUnexpectedInputException.php'
        );

        $this->assertSame($expected, $this->_generator->generate(
            $configuration,
            $namespaceName,
            $className
        ));
        $this->assertSame('Typhoon\Exception', $namespaceName);
        $this->assertSame('UnexpectedInputException', $className);
    }
}
