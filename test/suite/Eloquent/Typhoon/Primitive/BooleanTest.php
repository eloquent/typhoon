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

class BooleanTest extends \Eloquent\Typhoon\Test\TestCase
{
  /**
   * @covers Eloquent\Typhoon\Primitive\Boolean::type
   * @group primitives
   * @group primitive
   * @group core
   */
  public function testType()
  {
    $primitive = new Boolean(true);
    $this->assertInstanceOf('Eloquent\Typhoon\Type\BooleanType', $primitive->type());
  }
}
