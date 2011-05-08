<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Typhoon\Primitive;

use Typhoon\Test\TestCase;

class NullTest extends TestCase
{
  /**
   * @covers Typhoon\Primitive\Null::type
   * @group typhoon_scalars
   */
  public function testType()
  {
    $scalar = new Null(null);
    $this->assertInstanceOf('Typhoon\Type\Null', $scalar->type());
  }
}