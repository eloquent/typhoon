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

use Eloquent\Cosmos\ClassName;
use Eloquent\Typhoon\TestCase\MultiGenerationTestCase;

class RuntimeConfigurationTest extends MultiGenerationTestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->_validatorNamespace = ClassName::fromString('\foo');
        $this->_configuration = new RuntimeConfiguration(
            $this->_validatorNamespace,
            true
        );
    }

    public function testConstructor()
    {
        $this->assertSame($this->_validatorNamespace, $this->_configuration->validatorNamespace());
        $this->assertTrue($this->_configuration->useNativeCallable());
    }

    public function testConstructorDefaults()
    {
        $this->_configuration = new RuntimeConfiguration;

        $this->assertSame('\Typhoon', $this->_configuration->validatorNamespace()->string());
        $this->assertFalse($this->_configuration->useNativeCallable());
    }

    public function testSetValidatorNamespace()
    {
        $this->_validatorNamespace = ClassName::fromString('\foo');
        $this->_configuration->setValidatorNamespace($this->_validatorNamespace);

        $this->assertSame($this->_validatorNamespace, $this->_configuration->validatorNamespace());
    }

    public function testSetValidatorNamespaceNormalization()
    {
        $this->_configuration->setValidatorNamespace(ClassName::fromString('bar'));

        $this->assertSame('\bar', $this->_configuration->validatorNamespace()->string());
    }

    public function testSetUseNativeCallable()
    {
        $this->_configuration->setUseNativeCallable(true);

        $this->assertTrue($this->_configuration->useNativeCallable());
    }
}
