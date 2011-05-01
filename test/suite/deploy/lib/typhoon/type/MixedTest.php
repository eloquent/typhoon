<?php

namespace Typhoon\Type;

class MixedTest extends \Typhoon\Test\TypeTestCase
{
  protected function typeFixture()
  {
    return new Mixed;
  }

  protected function expectedString()
  {
    return 'mixed';
  }

  public function validValues()
  {
    return array(
      array(null),                      // null
      array(true),                      // boolean
      array('string'),                  // string
      array(1),                         // integer
      array(.1),                        // float
      array(array()),                   // array
      array(new \stdClass),             // object
      array(function(){}),              // closure
      array($this->resourceFixture()),  // resource
    );
  }
  
  public function invalidValues()
  {
    return array(array(null));
  }
  
  // methods below must be manually overridden to implement @covers
  
  /**
   * @covers \Typhoon\Type\Mixed::string
   * @group typhoon_types
   */
  public function testString() { parent::testString(); }
  
  /**
   * @covers \Typhoon\Type\Mixed::check
   * @dataProvider validValues
   * @group typhoon_types
   */
  public function testCheckPass($value) { parent::testCheckPass($value); }
  
  /**
   * @covers \Typhoon\Type\Mixed::check
   * @dataProvider invalidValues
   * @group typhoon_types
   */
  public function testCheckFailure($value) { $this->assertTrue(true); } // nothing fails
}