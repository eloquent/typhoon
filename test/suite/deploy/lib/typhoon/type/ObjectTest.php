<?php

namespace Typhoon\Type;

use stdClass;
use Typhoon\Test\TypeTestCase;

class ObjectTest extends TypeTestCase
{
  /**
   * @return array
   */
  public function typeValues()
  {
    $class = new \Typhoon\Primitive\String('\stdClass');

    return array(
      // object of any class
      array(false, null),                      // #0: null
      array(false, true),                      // #1: boolean
      array(false, 'string'),                  // #2: string
      array(false, 1),                         // #3: integer
      array(false, .1),                        // #4: float
      array(false, array()),                   // #5: array
      array(true,  new stdClass),              // #6: object
      array(true,  function(){}),              // #7: closure
      array(false, $this->resourceFixture()),  // #8: resource

      // object of a specific class
      array(true,  new stdClass,             array($class)),  // #9: object of correct class
      array(false, new Object,               array($class)),  // #10: object of incorrect class
      array(false, null,                     array($class)),  // #11: null
      array(false, true,                     array($class)),  // #12: boolean
      array(false, 'string',                 array($class)),  // #13: string
      array(false, 1,                        array($class)),  // #14: integer
      array(false, .1,                       array($class)),  // #15: float
      array(false, array(),                  array($class)),  // #16: array
      array(false, function(){},             array($class)),  // #17: closure
      array(false, $this->resourceFixture(), array($class)),  // #18: resource
    );
  }

  /**
   * @return string
   */
  protected function typeClass()
  {
    return __NAMESPACE__.'\Object';
  }

  /**
   * @return string
   */
  protected function expectedString()
  {
    return 'object';
  }

  /**
   * @covers \Typhoon\Type\Object::__construct
   * @covers \Typhoon\Type\Object::__toString
   * @group typhoon_types
   */
  public function testString()
  {
    parent::testString();

    $class = new \Typhoon\Primitive\String('\stdClass');
    $this->assertEquals($this->expectedString().'('.$class.')', (string)$this->typeFixture(array($class)));
  }

  // methods below must be manually overridden to implement @covers
  
  /**
   * @covers \Typhoon\Type\Object::__construct
   * @covers \Typhoon\Type\Object::check
   * @dataProvider typeValues
   * @group typhoon_types
   */
  public function testCheck($expected, $value, $arguments = null) { parent::testCheck($expected, $value, $arguments); }
}