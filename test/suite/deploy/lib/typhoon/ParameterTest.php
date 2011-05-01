<?php

namespace Typhoon;

class ParameterTest extends \PHPUnit_Framework_TestCase
{
  protected function setUp()
  {
    $this->_parameter = new Parameter;
    $this->_type = $this->getMockForAbstractClass(__NAMESPACE__.'\Type');
    $this->_mixed_type = new Type\Mixed;
  }

  /**
   * @covers \Typhoon\Parameter::setType
   * @covers \Typhoon\Parameter::type
   */
  public function testType()
  {
    $this->assertEquals($this->_mixed_type, $this->_parameter->type());
    
    $this->_parameter->setType($this->_type);

    $this->assertSame($this->_type, $this->_parameter->type());
  }

  /**
   * @var Parameter
   */
  protected $_parameter;

  /**
   * @var Type
   */
  protected $_type;

  /**
   * @var Type\Mixed
   */
  protected $_mixed_type;
}