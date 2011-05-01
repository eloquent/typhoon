<?php

namespace Typhoon\Type;

class ObjectTest extends \Typhoon\Test\TypeTestCase
{
  public function setUp()
  {
    $this->_class = '\stdClass';
  }

  protected function typeFixture()
  {
    return new Object;
  }

  protected function expectedString()
  {
    return 'object';
  }

  public function validValues()
  {
    return array(
      array(new \stdClass),             // object
      array(function(){}),              // closure
    );
  }
  
  public function invalidValues()
  {
    return array(
      array(null),                      // null
      array(true),                      // boolean
      array('string'),                  // string
      array(1),                         // integer
      array(.1),                        // float
      array(array()),                   // array
      array($this->resourceFixture()),  // resource
    );
  }

  /**
   * @covers \Typhoon\Type\Object::__construct
   * @covers \Typhoon\Type\Object::string
   */
  public function testConstructorAndString()
  {
    $type = new Object($this->_class);

    $this->assertEquals($this->expectedString().'('.$this->_class.')', $type->string());
  }

  /**
   * @covers \Typhoon\Type\Object::__construct
   * @covers \Typhoon\Type\Object::check
   */
  public function testConstructorAndCheck()
  {
    $type = new Object($this->_class);

    $this->assertTrue($type->check(new \stdClass()));
    $this->assertFalse($type->check($type));
    $this->assertFalse($type->check(true));
  }
  
  // methods below must be manually overridden to implement @covers
  
  /**
   * @covers \Typhoon\Type\Object::string
   * @group typhoon_types
   */
  public function testString() { parent::testString(); }
  
  /**
   * @covers \Typhoon\Type\Object::check
   * @dataProvider validValues
   * @group typhoon_types
   */
  public function testCheckPass($value) { parent::testCheckPass($value); }
  
  /**
   * @covers \Typhoon\Type\Object::check
   * @dataProvider invalidValues
   * @group typhoon_types
   */
  public function testCheckFailure($value) { parent::testCheckFailure($value); }

  /**
   * @var string
   */
  protected $_class;
}