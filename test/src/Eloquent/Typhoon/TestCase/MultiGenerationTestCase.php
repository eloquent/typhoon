<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\TestCase;

use Ezzatron\PHPUnit\ParameterizedTestCase;
use LogicException;
use Typhoon\Typhoon;

abstract class MultiGenerationTestCase extends ParameterizedTestCase
{
    public function getTestCaseParameters()
    {
        return array(
            array('runtime'),
            // array('pre-generated'),
        );
    }

    public function setUpParameterized($generationType)
    {
        Typhoon::setRuntimeGeneration('runtime' === $generationType);
    }

    public function tearDownParameterized($generationType)
    {
        if (Typhoon::runtimeGeneration() !== ('runtime' === $generationType)) {
            throw new LogicException('Test changed global runtime generation setting.');
        }
    }
}
