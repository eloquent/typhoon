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

class CallbackTest extends TestCase
{
  /**
   * @covers Typhoon\Primitive\Callback::type
   * @group typhoon_primitives
   */
  public function testType()
  {
    $primitive = new Callback(array($this, 'testType'));
    $this->assertInstanceOf('Typhoon\Type\Callback', $primitive->type());
  }
}