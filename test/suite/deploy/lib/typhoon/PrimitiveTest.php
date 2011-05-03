<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Typhoon;

use PHPUnit_Framework_TestCase;

class PrimitiveTest extends PHPUnit_Framework_TestCase
{
  /**
   * @covers \Typhoon\Primitive::__construct
   * @covers \Typhoon\Primitive::type
   * @covers \Typhoon\Primitive::value
   * @covers \Typhoon\Primitive::__toString
   */
  public function testPrimitive()
  {
    $value = 'foo';

    $type = $this->getMock(
      __NAMESPACE__.'\Type',
      array('check', 'assert', '__toString'),
      array(),
      'TypeMock'
     );
    $type
      ->expects($this->once())
      ->method('assert')
      ->with($this->equalTo($value))
      ->will($this->returnCallback(function($value) { return $value; }))
    ;

    $scalar = $this->getMockForAbstractClass(
      __NAMESPACE__.'\Primitive',
      array(),
      'PrimitiveMock',
      false
     );
    $scalar
      ->expects($this->once())
      ->method('type')
      ->will($this->returnValue($type))
    ;

    $scalar->__construct($value);

    $this->assertEquals($value, $scalar->value());
    $this->assertEquals((string)$value, (string)$scalar);
  }
}