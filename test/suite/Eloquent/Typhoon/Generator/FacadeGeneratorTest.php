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
use Phake;

class FacadeGeneratorTest extends MultiGenerationTestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->_renderer = new Renderer;
        $this->_configurationGenerator = new RuntimeConfigurationGenerator;
        $this->_generator = Phake::partialMock(
            __NAMESPACE__.'\FacadeGenerator',
            $this->_renderer,
            $this->_configurationGenerator
        );
    }

    public function testConstructor()
    {
        $this->assertSame($this->_renderer, $this->_generator->renderer());
        $this->assertSame($this->_configurationGenerator, $this->_generator->configurationGenerator());
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

    public function generateData()
    {
        $exampleClassesPath =
            __DIR__.
            '/../../../../src/Eloquent/Typhoon/TestFixture/GeneratorExamples/'
        ;

        $data = array();
        foreach (scandir($exampleClassesPath) as $item) {
            if ('.' !== substr($item, 0, 1)) {
                $className = pathinfo($item, PATHINFO_FILENAME);
                $data[$className] = array($className);
            }
        }

        return $data;
    }

    public function testGenerate()
    {
        $configuration = new RuntimeConfiguration(false);
        $expected = file_get_contents(
            __DIR__.
            '/../../../../src/Typhoon/Eloquent/Typhoon/TestFixture/ExampleTyphoon.php'
        );

        $this->assertSame($expected, $this->_generator->generate($configuration));
    }
}