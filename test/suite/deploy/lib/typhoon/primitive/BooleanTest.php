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

class BooleanTest extends TestCase
{
  /**
   * @covers Typhoon\Primitive\Boolean::type
   * @group typhoon_scalars
   */
  public function testType()
  {
    $scalar = new Boolean(true);
    $this->assertInstanceOf('Typhoon\Type\Boolean', $scalar->type());
  }
}