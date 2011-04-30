<?php

namespace Typhoon;

class TypeTest extends \PHPUnit_Framework_TestCase
{
  protected function setUp()
  {
    $this->_type = $this->getMockForAbstractClass('\Typhoon\Type');
  }
  
  /**
   * @covers \Typhoon\Type::assert
   */
  public function testAssertPass()
  {
    $value = 'foo';
    
    $this->_type
      ->expects($this->once())
      ->method('check')
      ->with($value)
      ->will($this->returnValue(true))
    ;

    $this->assertEquals($value, $this->_type->assert($value));
  }
  
  /**
   * @covers \Typhoon\Type::assert
   */
  public function testAssertFailure()
  {
    $value = 'foo';
    
    $this->_type
      ->expects($this->once())
      ->method('check')
      ->with($value)
      ->will($this->returnValue(false))
    ;
    $this->_type
      ->expects($this->once())
      ->method('string')
      ->will($this->returnValue(''))
    ;

    $this->setExpectedException('\Typhoon\Type\Exception\UnexpectedType');
    $this->_type->assert($value);
  }
  
  /**
   * @covers \Typhoon\Type::__toString
   */
  public function testToString()
  {
    $this->_type
      ->expects($this->once())
      ->method('string')
      ->will($this->returnValue('foo'))
    ;

    $this->assertEquals('foo', (string)$this->_type);
  }

  /**
   * @var Type
   */
  protected $_type;
}