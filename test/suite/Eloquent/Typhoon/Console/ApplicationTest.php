<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Console;

use Eloquent\Typhoon\TestCase\MultiGenerationTestCase;
use Phake;

class ApplicationTest extends MultiGenerationTestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->_configurationLoader = Phake::mock(
            'Eloquent\Typhoon\Configuration\ConfigurationLoader'
        );
        $this->_application = new Application(
            $this->_configurationLoader
        );
    }

    public function testConstructor()
    {
        $this->assertSame(
            $this->_configurationLoader,
            $this->_application->configurationLoader()
        );
        $this->assertSame('Typhoon', $this->_application->getName());
        $this->assertSame('DEV', $this->_application->getVersion());
        $this->assertInstanceOf(
            __NAMESPACE__.'\Command\GenerateValidatorsCommand',
            $this->_application->get('generate:validators')
        );
    }

    public function testConstructorDefaults()
    {
        $application = new Application;

        $this->assertInstanceOf(
            'Eloquent\Typhoon\Configuration\ConfigurationLoader',
            $this->_application->configurationLoader()
        );
    }
}
