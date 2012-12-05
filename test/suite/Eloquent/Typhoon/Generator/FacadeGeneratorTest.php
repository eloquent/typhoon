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

use Eloquent\Typhoon\Configuration\RuntimeConfiguration;
use Eloquent\Typhoon\TestCase\MultiGenerationTestCase;
use Icecave\Rasta\Renderer;

class FacadeGeneratorTest extends MultiGenerationTestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->_renderer = new Renderer;
        $this->_configurationGenerator = new RuntimeConfigurationGenerator;
        $this->_generator = new FacadeGenerator(
            $this->_renderer,
            $this->_configurationGenerator
        );
    }

    public function testConstructor()
    {
        $this->assertSame($this->_renderer, $this->_generator->renderer());
        $this->assertSame(
            $this->_configurationGenerator,
            $this->_generator->configurationGenerator()
        );
    }

    public function testConstructorDefaults()
    {
        $this->_generator = new FacadeGenerator;

        $this->assertInstanceOf(
            'Icecave\Rasta\Renderer',
            $this->_generator->renderer()
        );
        $this->assertInstanceOf(
            __NAMESPACE__.'\RuntimeConfigurationGenerator',
            $this->_generator->configurationGenerator()
        );
    }

    public function testGenerate()
    {
        $configuration = new RuntimeConfiguration('foo', true);
        $expected = file_get_contents(
            __DIR__.
            '/../../../../src/Eloquent/Typhoon/TestFixture/GeneratorExamples/ExampleTypeCheck.php'
        );

        $this->assertSame($expected, $this->_generator->generate(
            $configuration,
            $namespaceName,
            $className
        ));
        $this->assertSame('foo', $namespaceName);
        $this->assertSame('TypeCheck', $className);
    }

    public function testGenerateNoCallable()
    {
        $configuration = new RuntimeConfiguration('foo', false);
        $expected = file_get_contents(
            __DIR__.
            '/../../../../src/Eloquent/Typhoon/TestFixture/GeneratorExamples/ExampleTypeCheckNoCallable.php'
        );

        $this->assertSame($expected, $this->_generator->generate(
            $configuration,
            $namespaceName,
            $className
        ));
        $this->assertSame('foo', $namespaceName);
        $this->assertSame('TypeCheck', $className);
    }
}
