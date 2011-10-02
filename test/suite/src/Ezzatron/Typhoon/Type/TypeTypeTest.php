<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ezzatron\Typhoon\Type;

use Phake;
use stdClass;
use Ezzatron\Typhoon\Attribute\Attributes;

class TypeTypeTest extends \Ezzatron\Typhoon\Test\TypeTestCase
{
  /**
   * @return array
   */
  public function typeValues()
  {
    return array(
      array(false, null),                        // #0: null
      array(false, true),                        // #1: boolean
      array(false, 'string'),                    // #2: string
      array(false, 1),                           // #3: integer
      array(false, .1),                          // #4: float
      array(false, array()),                     // #5: array
      array(false, new stdClass),                // #6: object
      array(false, function(){}),                // #7: closure
      array(false, $this->resourceFixture()),    // #8: resource

      array(true,  Phake::mock('Ezzatron\Typhoon\Type\Type'))  // #9: typhoon type
    );
  }

  /**
   * @return string
   */
  protected function typeClass()
  {
    return __NAMESPACE__.'\TypeType';
  }

  // methods below must be manually overridden to implement @covers

  /**
   * @covers Ezzatron\Typhoon\Type\TypeType::typhoonCheck
   * @dataProvider typeValues
   * @group typhoon_types
   */
  public function testTyphoonCheck($expected, $value, Attributes $attributes = null) { parent::testTyphoonCheck($expected, $value, $attributes); }
}