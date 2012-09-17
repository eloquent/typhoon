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

        $this->_configurationReader = Phake::mock(
            'Eloquent\Typhoon\Configuration\ConfigurationReader'
        );
        $this->_application = new Application(
            $this->_configurationReader
        );
    }

    public function testConstructor()
    {
        $this->assertSame(
            $this->_configurationReader,
            $this->_application->configurationReader()
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
            'Eloquent\Typhoon\Configuration\ConfigurationReader',
            $this->_application->configurationReader()
        );
    }
}
