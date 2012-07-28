<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Primitive;

class NumericTest extends \Eloquent\Typhoon\Test\TestCase
{
  /**
   * @covers Eloquent\Typhoon\Primitive\Numeric::type
   * @group primitives
   * @group primitive
   */
  public function testType()
  {
    $primitive = new Numeric(1);
    $this->assertInstanceOf('Eloquent\Typhoon\Type\NumericType', $primitive->type());
  }
}
