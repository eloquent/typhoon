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

class ObjectTest extends \Ezzatron\Typhoon\Test\TestCase
{
  /**
   * @covers Ezzatron\Typhoon\Primitive\Object::type
   * @group primitives
   * @group primitive
   */
  public function testType()
  {
    $primitive = new Object(new \stdClass);
    $this->assertInstanceOf('Ezzatron\Typhoon\Type\ObjectType', $primitive->type());
  }
}
