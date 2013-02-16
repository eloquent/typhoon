<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Configuration;

use Eloquent\Typhoon\TestCase\MultiGenerationTestCase;

class ConfigurationTest extends MultiGenerationTestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->_configuration = new Configuration(
            'foo',
            array('bar', 'baz')
        );
    }

    public function testConstructor()
    {
        $this->assertSame('foo', $this->_configuration->outputPath());
        $this->assertSame(array('bar', 'baz'), $this->_configuration->sourcePaths());
        $this->assertSame(array('vendor/autoload.php'), $this->_configuration->loaderPaths());
        $this->assertTrue($this->_configuration->useNativeCallable());
    }

    public function testConstructorFailureEmptySourcePaths()
    {
        $this->setExpectedException(
            __NAMESPACE__.'\Exception\InvalidConfigurationException',
            "Invalid configuration. 'sourcePaths' must not be empty."
        );
        new Configuration(
            'foo',
            array()
        );
    }

    public function testOutputPath()
    {
        $this->assertSame('foo', $this->_configuration->outputPath());

        $this->_configuration->setOutputPath('qux');

        $this->assertSame('qux', $this->_configuration->outputPath());
    }

    public function testSourcePaths()
    {
        $this->assertSame(array('bar', 'baz'), $this->_configuration->sourcePaths());

        $this->_configuration->setSourcePaths(array('qux'));

        $this->assertSame(array('qux'), $this->_configuration->sourcePaths());
    }

    public function testSourcePathsFailureEmptySourcePaths()
    {
        $this->setExpectedException(
            __NAMESPACE__.'\Exception\InvalidConfigurationException',
            "Invalid configuration. 'sourcePaths' must not be empty."
        );
        $this->_configuration->setSourcePaths(array());
    }

    public function testLoaderPaths()
    {
        $this->assertSame(array('vendor/autoload.php'), $this->_configuration->loaderPaths());

        $this->_configuration->setLoaderPaths(array('qux'));

        $this->assertSame(array('qux'), $this->_configuration->loaderPaths());
    }

    public function testUseNativeCallable()
    {
        $this->assertTrue($this->_configuration->useNativeCallable());

        $this->_configuration->setUseNativeCallable(false);

        $this->assertFalse($this->_configuration->useNativeCallable());
    }
}
