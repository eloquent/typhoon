<?php

namespace Typhoon;

class TypeTest extends \PHPUnit_Framework_TestCase
{
  protected function setUp()
  {
    $this->_type = $this->getMockForAbstractClass('Typhoon\Type');
  }
  
  /**
   * @covers Typhoon\Type::__toString
   */
  public function testToString()
  {
    $this->_type->expects($this->once())
       ->method('string')
       ->will($this->returnValue('foo'));

    $this->assertEquals('foo', (string)$this->_type);
  }

  /**
   * @var TestType
   */
  protected $_type;
}