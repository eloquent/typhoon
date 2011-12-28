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

class CallbackTest extends \Ezzatron\Typhoon\Test\TestCase
{
  /**
   * @covers Ezzatron\Typhoon\Primitive\Callback::type
   * @group primitives
   * @group primitive
   */
  public function testType()
  {
    $primitive = new Callback(array($this, 'testType'));
    $this->assertInstanceOf('Ezzatron\Typhoon\Type\CallbackType', $primitive->type());
  }
}