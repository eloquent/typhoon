<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Typhoon\Type;

use stdClass;
use Typhoon\Test\TypeTestCase;

class MixedTest extends TypeTestCase
{
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
      array(true, new stdClass),              // #6: object
      array(true, function(){}),              // #7: closure
      array(true, $this->resourceFixture()),  // #8: resource
    );
  }
  
  /**
   * @return string
   */
  protected function typeClass()
  {
    return __NAMESPACE__.'\Mixed';
  }

  /**
   * @return string
   */
  protected function expectedString()
  {
    return 'mixed';
  }

  // methods below must be manually overridden to implement @covers
  
  /**
   * @covers Typhoon\Type\Mixed::__toString
   * @group typhoon_types
   */
  public function testString() { parent::testString(); }
  
  /**
   * @covers Typhoon\Type\Mixed::check
   * @dataProvider typeValues
   * @group typhoon_types
   */
  public function testCheck($expected, $value, $arguments = null) { parent::testCheck($expected, $value, $arguments); }
}