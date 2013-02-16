<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Typhoon;

use PHPUnit_Framework_TestCase;

class DummyValidatorTest extends PHPUnit_Framework_TestCase
{
    public function testCall()
    {
        $dummy = new DummyValidator;

        $this->assertNull($dummy->foo('bar'));
        $this->assertNull($dummy->baz('qux', 'doom'));
        $this->assertNull($dummy->splat());
    }
}
