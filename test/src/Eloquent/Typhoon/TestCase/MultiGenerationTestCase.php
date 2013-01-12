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

use Eloquent\Typhoon\TypeCheck\TypeCheck;
use Ezzatron\PHPUnit\ParameterizedTestCase;
use LogicException;

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
        TypeCheck::setRuntimeGeneration('runtime' === $generationType);
    }

    public function tearDownParameterized($generationType)
    {
        if (TypeCheck::runtimeGeneration() !== ('runtime' === $generationType)) {
            throw new LogicException('Test changed global runtime generation setting.');
        }
    }
}
