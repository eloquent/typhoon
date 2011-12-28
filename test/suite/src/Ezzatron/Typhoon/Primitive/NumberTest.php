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

class NumberTest extends \Ezzatron\Typhoon\Test\TestCase
{
  /**
   * @covers Ezzatron\Typhoon\Primitive\Number::type
   * @group primitives
   * @group primitive
   */
  public function testType()
  {
    $primitive = new Number(1);
    $this->assertInstanceOf('Ezzatron\Typhoon\Type\NumberType', $primitive->type());
  }
}