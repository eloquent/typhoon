<?php

namespace Typhoon;

use PHPUnit_Framework_TestCase;
use Typhoon\Primitive\String as StringPrimitive;
use Typhoon\Type\Mixed as MixedType;

class ParameterTest extends PHPUnit_Framework_TestCase
{
  protected function setUp()
  {
    $this->_parameter = new Parameter;
    $this->_type = $this->getMockForAbstractClass(__NAMESPACE__.'\Type');
  }

  /**
   * @covers \Typhoon\Parameter::setType
   * @covers \Typhoon\Parameter::type
   */
  public function testType()
  {
    $this->assertEquals(new MixedType, $this->_parameter->type());
    
    $this->_parameter->setType($this->_type);

    $this->assertSame($this->_type, $this->_parameter->type());
  }

  /**
   * @covers \Typhoon\Parameter::setName
   * @covers \Typhoon\Parameter::name
   */
  public function testName()
  {
    $this->assertNull($this->_parameter->name());

    $name = new StringPrimitive('foo');
    $this->_parameter->setName($name);

    $this->assertEquals($name, $this->_parameter->name());
  }

  /**
   * @covers \Typhoon\Parameter::setDescription
   * @covers \Typhoon\Parameter::description
   */
  public function testDescription()
  {
    $this->assertNull($this->_parameter->description());

    $description = new StringPrimitive('foo');
    $this->_parameter->setDescription($description);

    $this->assertEquals($description, $this->_parameter->description());
  }

  /**
   * @var Parameter
   */
  protected $_parameter;

  /**
   * @var Type
   */
  protected $_type;
}