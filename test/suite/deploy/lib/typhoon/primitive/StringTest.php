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

use PHPUnit_Framework_TestCase;

class StringTest extends PHPUnit_Framework_TestCase
{
  /**
   * @covers \Typhoon\Primitive\String::type
   * @group typhoon_scalars
   */
  public function testType()
  {
    $scalar = new String('');
    $this->assertInstanceOf('\Typhoon\Type\String', $scalar->type());
  }
}