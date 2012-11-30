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

        $this->_configuration = new RuntimeConfiguration(
            'foo',
            false
        );
    }

    public function testConstructor()
    {
        $this->assertSame('foo', $this->_configuration->validatorNamespace());
        $this->assertFalse($this->_configuration->useNativeCallable());
    }

    public function testConstructorDefaults()
    {
        $this->_configuration = new RuntimeConfiguration;

        $this->assertSame('Typhoon', $this->_configuration->validatorNamespace());
        $this->assertTrue($this->_configuration->useNativeCallable());
    }

    public function testSetValidatorNamespace()
    {
        $this->_configuration->setValidatorNamespace('bar');

        $this->assertSame('bar', $this->_configuration->validatorNamespace());
    }

    public function testSetUseNativeCallable()
    {
        $this->_configuration->setUseNativeCallable(false);

        $this->assertFalse($this->_configuration->useNativeCallable());
    }
}
