<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ezzatron\Typhoon\Primitive;

class NumericTest extends \Ezzatron\Typhoon\Test\TestCase
{
  /**
   * @covers Ezzatron\Typhoon\Primitive\Numeric::type
   * @group typhoon_primitives
   */
  public function testType()
  {
    $primitive = new Numeric(1);
    $this->assertInstanceOf('Ezzatron\Typhoon\Type\NumericType', $primitive->type());
  }
}