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

class StringableTest extends \Eloquent\Typhoon\Test\TestCase
{
  /**
   * @covers Eloquent\Typhoon\Primitive\Stringable::type
   * @group primitives
   * @group primitive
   */
  public function testType()
  {
    $primitive = new Stringable('');
    $this->assertInstanceOf('Eloquent\Typhoon\Type\StringableType', $primitive->type());
  }
}
