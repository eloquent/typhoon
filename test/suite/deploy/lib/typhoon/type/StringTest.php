<?php

namespace Typhoon\Type;

class StringTest extends \Typhoon\Test\TypeTestCase
{
  /**
   * @return string
   */
  protected function typeClass()
  {
    return '\Typhoon\Type\String';
  }

  /**
   * @return string
   */
  protected function expectedString()
  {
    return 'string';
  }

  /**
   * @return array
   */
  public function typeValues()
  {
    return array(
      array(false, null),                      // #0: null
      array(false, true),                      // #1: boolean
      array(true,  'string'),                  // #2: string
      array(false, 1),                         // #3: integer
      array(false, .1),                        // #4: float
      array(false, array()),                   // #5: array
      array(false, new \stdClass),             // #6: object
      array(false, function(){}),              // #7: closure
      array(false, $this->resourceFixture()),  // #8: resource
    );
  }

  // methods below must be manually overridden to implement @covers
  
  /**
   * @covers \Typhoon\Type\String::string
   * @group typhoon_types
   */
  public function testString() { parent::testString(); }
  
  /**
   * @covers \Typhoon\Type\String::check
   * @dataProvider typeValues
   * @group typhoon_types
   */
  public function testCheck($expected, $value, $arguments = null) { parent::testCheck($expected, $value, $arguments); }
}