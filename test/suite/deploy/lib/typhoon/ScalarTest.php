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

    $this->_type = $this->getMock(
      __NAMESPACE__.'\Type',
      array('string', 'check', 'assert'),
      array(),
      'TypeMock'
     );
    $this->_type
      ->expects($this->once())
      ->method('assert')
      ->with($this->equalTo($value))
      ->will($this->returnCallback(function($value) { return $value; }))
    ;

    $this->_scalar = $this->getMockForAbstractClass(
      __NAMESPACE__.'\Scalar',
      array(),
      'ScalarMock',
      false
     );
    $this->_scalar
      ->expects($this->once())
      ->method('type')
      ->will($this->returnValue($this->_type))
    ;

    $this->_scalar->__construct($value);

    $this->assertEquals($value, $this->_scalar->value());
    $this->assertEquals((string)$value, (string)$this->_scalar);
  }

  /**
   * @var Scalar
   */
  protected $_scalar;
}