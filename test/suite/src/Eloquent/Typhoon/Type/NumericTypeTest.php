<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Type;

use Eloquent\Typhax\IntrinsicType\IntrinsicTypeName;
use stdClass;

class NumericTypeTest extends \Eloquent\Typhoon\Test\TypeTestCase
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
      array(true,  1),                         // #3: integer
      array(true,  .1),                        // #4: float
      array(false, array()),                   // #5: array
      array(false, new stdClass),              // #6: object
      array(false, function(){}),              // #7: closure
      array(false, $this->resourceFixture()),  // #8: resource

      array(true,  '1'),    // #9: numeric string
      array(true,  '.1'),   // #10: numeric string
      array(true,  '1e4'),  // #11: numeric string
    );
  }

  /**
   * @return string
   */
  protected function typeClass()
  {
    return __NAMESPACE__.'\NumericType';
  }

  /**
   * @return string
   */
  protected function typeName()
  {
    return IntrinsicTypeName::NAME_NUMERIC()->value();
  }

  // methods below must be manually overridden to implement @covers

  /**
   * @covers Eloquent\Typhoon\Type\NumericType::typhoonCheck
   * @dataProvider typeValues
   * @group types
   * @group type
   */
  public function testTyphoonCheck($expected, $value, $attributes = null) { parent::testTyphoonCheck($expected, $value, $attributes); }

  /**
   * @covers Eloquent\Typhoon\Type\NumericType::typhoonName
   * @group types
   */
  public function testTyphoonName() { parent::testTyphoonName(); }
}
