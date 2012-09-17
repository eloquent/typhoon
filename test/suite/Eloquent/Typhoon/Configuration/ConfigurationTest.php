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

class ConfigurationTest extends MultiGenerationTestCase
{
    public function testConfiguration()
    {
        $configuration = new Configuration(
            'foo',
            array('bar', 'baz'),
            array('qux', 'doom'),
            true
        );

        $this->assertSame('foo', $configuration->outputPath());
        $this->assertSame(array('bar', 'baz'), $configuration->sourcePaths());
        $this->assertSame(array('qux', 'doom'), $configuration->loaderPaths());
        $this->assertTrue($configuration->useNativeCallable());
    }

    public function testConfigurationFailureEmptySourcePaths()
    {
        $this->setExpectedException(
            __NAMESPACE__.'\Exception\InvalidConfigurationException',
            "Invalid configuration. 'sourcePaths' must not be empty."
        );
        new Configuration(
            'foo',
            array(),
            array(),
            true
        );
    }
}
