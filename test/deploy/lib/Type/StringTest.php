<?php

namespace Typhoon\Type;

class StringTest extends \Typhoon\Test\TypeTestCase
{
  protected function typeFixture()
  {
    return new String;
  }

  protected function expectedString()
  {
    return 'string';
  }

  public function validValues()
  {
    return array(
      array('string'),                  // string
    );
  }
  
  public function invalidValues()
  {
    return array(
      array(null),                      // null
      array(true),                      // boolean
      array(1),                         // integer
      array(.1),                        // float
      array(array()),                   // array
      array(new \stdClass),             // object
      array(function(){}),              // closure
      array($this->resourceFixture()),  // resource
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
   * @dataProvider validValues
   * @group typhoon_types
   */
  public function testCheckPass($value) { parent::testCheckPass($value); }
  
  /**
   * @covers \Typhoon\Type\String::check
   * @dataProvider invalidValues
   * @group typhoon_types
   */
  public function testCheckFailure($value) { parent::testCheckFailure($value); }
}