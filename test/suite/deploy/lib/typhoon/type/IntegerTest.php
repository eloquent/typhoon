<?php

namespace Typhoon\Type;

use stdClass;
use Typhoon\Test\TypeTestCase;

class IntegerTest extends TypeTestCase
{
  /**
   * @return array
   */
  public function typeValues()
  {
    return array(
      array(false, null),                      // #0: null
      array(false, true),                      // #1: boolean
      array(false, 'string'),                  // #2: string
      array(true, 1),                          // #3: integer
      array(false, .1),                        // #4: float
      array(false, array()),                   // #5: array
      array(false, new stdClass),              // #6: object
      array(false, function(){}),              // #7: closure
      array(false, $this->resourceFixture()),  // #8: resource
      array(false, '1'),                       // #9: integer string
      array(false, 1.0),                       // #10: whole number float
      array(true, -1),                         // #11: negative integer
    );
  }

  /**
   * @return string
   */
  protected function typeClass()
  {
    return __NAMESPACE__.'\Integer';
  }

  /**
   * @return string
   */
  protected function expectedString()
  {
    return 'integer';
  }

  // methods below must be manually overridden to implement @covers
  
  /**
   * @covers \Typhoon\Type\Integer::string
   * @group typhoon_types
   */
  public function testString() { parent::testString(); }
  
  /**
   * @covers \Typhoon\Type\Integer::check
   * @dataProvider typeValues
   * @group typhoon_types
   */
  public function testCheck($expected, $value, $arguments = null) { parent::testCheck($expected, $value, $arguments); }
}