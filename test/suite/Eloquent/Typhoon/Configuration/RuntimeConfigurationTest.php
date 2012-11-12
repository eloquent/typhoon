<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Configuration;

use Eloquent\Typhoon\TestCase\MultiGenerationTestCase;

class RuntimeConfigurationTest extends MultiGenerationTestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->_configuration = new RuntimeConfiguration;
    }

    public function testConstructor()
    {
        $this->assertTrue($this->_configuration->useNativeCallable());
        $this->assertFalse($this->_configuration->runtimeGeneration());

        $this->_configuration = new RuntimeConfiguration(false, true);

        $this->assertFalse($this->_configuration->useNativeCallable());
        $this->assertTrue($this->_configuration->runtimeGeneration());

        $this->_configuration = new RuntimeConfiguration(true, false);

        $this->assertTrue($this->_configuration->useNativeCallable());
        $this->assertFalse($this->_configuration->runtimeGeneration());
    }

    public function testUseNativeCallable()
    {
        $this->assertTrue($this->_configuration->useNativeCallable());

        $this->_configuration->setUseNativeCallable(false);

        $this->assertFalse($this->_configuration->useNativeCallable());
    }

    public function testRuntimeGeneration()
    {
        $this->assertFalse($this->_configuration->runtimeGeneration());

        $this->_configuration->setRuntimeGeneration(true);

        $this->assertTrue($this->_configuration->runtimeGeneration());
    }
}
