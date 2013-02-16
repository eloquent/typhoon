<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Generator;

use Eloquent\Typhoon\TestCase\MultiGenerationTestCase;
use Phake;

class NullifiedTypeTest extends MultiGenerationTestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->_originalType = Phake::mock('Eloquent\Typhax\Type\Type');
        $this->_nullifiedType = new NullifiedType($this->_originalType);
    }

    public function testConstructor()
    {
        $this->assertSame($this->_originalType, $this->_nullifiedType->originalType());
    }
}
