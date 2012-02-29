<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Type;

use Eloquent\Typhoon\Test\Fixture\ConcreteBaseType;
use Phake;

class BaseTypeTest extends \Eloquent\Typhoon\Test\TestCase
{
  /**
   * @covers Eloquent\Typhoon\Type\BaseType::equalsTyphoonType
   * @group type
   * @group core
   */
  public function testEqualsTyphoonType()
  {
    $left = new ConcreteBaseType;
    $right = new ConcreteBaseType;

    $this->assertTrue($left->equalsTyphoonType($left));
    $this->assertTrue($left->equalsTyphoonType($right));
    $this->assertTrue($right->equalsTyphoonType($right));
    $this->assertTrue($right->equalsTyphoonType($left));

    $left = new ConcreteBaseType;
    $left->foo = 'bar';
    $right = new ConcreteBaseType;
    $right->foo = 'baz';

    $this->assertFalse($left->equalsTyphoonType($right));
    $this->assertFalse($right->equalsTyphoonType($left));
  }
}
