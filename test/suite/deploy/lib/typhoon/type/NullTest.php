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

class NullTest extends TypeTestCase
{
  /**
   * @return array
   */
  public function typeValues()
  {
    return array(
      array(true,  null),                      // #0: null
      array(false, true),                      // #1: boolean
      array(false, 'string'),                  // #2: string
      array(false, 1),                         // #3: integer
      array(false, .1),                        // #4: float
      array(false, array()),                   // #5: array
      array(false, new stdClass),              // #6: object
      array(false, function(){}),              // #7: closure
      array(false, $this->resourceFixture()),  // #8: resource
    );
  }

  /**
   * @return string
   */
  protected function typeClass()
  {
    return __NAMESPACE__.'\Null';
  }

  /**
   * @return string
   */
  protected function expectedString()
  {
    return 'null';
  }

  // methods below must be manually overridden to implement @covers

  /**
   * @covers Typhoon\Type\Null::__toString
   * @group typhoon_types
   */
  public function testString() { parent::testString(); }

  /**
   * @covers Typhoon\Type\Null::check
   * @dataProvider typeValues
   * @group typhoon_types
   */
  public function testCheck($expected, $value, $arguments = null) { parent::testCheck($expected, $value, $arguments); }
}