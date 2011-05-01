<?php

namespace Typhoon;

use PHPUnit_Framework_TestCase;

class ScalarTest extends PHPUnit_Framework_TestCase
{
  /**
   * @covers \Typhoon\Scalar::__construct
   * @covers \Typhoon\Scalar::type
   * @covers \Typhoon\Scalar::value
   * @covers \Typhoon\Scalar::__toString
   */
  public function testScalar()
  {
    $value = 'foo';

    $type = $this->getMock(
      __NAMESPACE__.'\Type',
      array('string', 'check', 'assert'),
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
      __NAMESPACE__.'\Scalar',
      array(),
      'ScalarMock',
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