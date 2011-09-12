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

class BooleanTest extends \Ezzatron\Typhoon\Test\TestCase
{
  /**
   * @covers Ezzatron\Typhoon\Primitive\Boolean::type
   * @group typhoon_primitives
   */
  public function testType()
  {
    $primitive = new Boolean(true);
    $this->assertInstanceOf('Ezzatron\Typhoon\Type\BooleanType', $primitive->type());
  }
}