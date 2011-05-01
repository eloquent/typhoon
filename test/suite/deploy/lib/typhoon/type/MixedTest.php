<?php

namespace Typhoon\Type;

class MixedTest extends \Typhoon\Test\TypeTestCase
{
  /**
   * @return Mixed
   */
  protected function typeFixture(array $arguments = null)
  {
    return new Mixed;
  }

  /**
   * @return string
   */
  protected function expectedString()
  {
    return 'mixed';
  }

  /**
   * @return array
   */
  public function typeValues()
  {
    return array(
      array(true, null),                      // #0: null
      array(true, true),                      // #1: boolean
      array(true, 'string'),                  // #2: string
      array(true, 1),                         // #3: integer
      array(true, .1),                        // #4: float
      array(true, array()),                   // #5: array
      array(true, new \stdClass),             // #6: object
      array(true, function(){}),              // #7: closure
      array(true, $this->resourceFixture()),  // #8: resource
    );
  }

  // methods below must be manually overridden to implement @covers
  
  /**
   * @covers \Typhoon\Type\Mixed::string
   * @group typhoon_types
   */
  public function testString() { parent::testString(); }
  
  /**
   * @covers \Typhoon\Type\Mixed::check
   * @dataProvider typeValues
   * @group typhoon_types
   */
  public function testCheck($expected, $value, $arguments = null) { parent::testCheck($expected, $value, $arguments); }
}