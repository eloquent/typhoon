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

class ConfigurationOptionTest extends MultiGenerationTestCase
{
    public function testEnumValues()
    {
        $expected = array(
            'LOADER_PATHS' => ConfigurationOption::LOADER_PATHS(),
            'OUTPUT_PATH' => ConfigurationOption::OUTPUT_PATH(),
            'SOURCE_PATHS' => ConfigurationOption::SOURCE_PATHS(),
            'USE_NATIVE_CALLABLE' => ConfigurationOption::USE_NATIVE_CALLABLE(),
            'VALIDATOR_NAMESPACE' => ConfigurationOption::VALIDATOR_NAMESPACE(),
        );

        $this->assertSame($expected, ConfigurationOption::multitonInstances());
    }

    public function testUndefinedInstanceFailure()
    {
        $this->setExpectedException(
            __NAMESPACE__.'\Exception\UndefinedConfigurationOptionException'
        );
        ConfigurationOption::FOO();
    }
}
