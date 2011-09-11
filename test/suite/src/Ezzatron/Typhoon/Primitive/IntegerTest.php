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

use Ezzatron\Typhoon\Test\TestCase;

class IntegerTest extends TestCase
{
  /**
   * @covers Ezzatron\Typhoon\Primitive\Integer::type
   * @group typhoon_primitives
   */
  public function testType()
  {
    $primitive = new Integer(1);
    $this->assertInstanceOf('Ezzatron\Typhoon\Type\Integer', $primitive->type());
  }
}