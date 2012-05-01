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

class IntegerTypeTest extends \Eloquent\Typhoon\Test\TypeTestCase
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
    return __NAMESPACE__.'\IntegerType';
  }

  /**
   * @return string
   */
  protected function typeName()
  {
    return IntrinsicTypeName::NAME_INTEGER()->_value();
  }

  // methods below must be manually overridden to implement @covers

  /**
   * @covers Eloquent\Typhoon\Type\IntegerType::typhoonCheck
   * @dataProvider typeValues
   * @group types
   * @group type
   * @group core
   */
  public function testTyphoonCheck($expected, $value, $attributes = null) { parent::testTyphoonCheck($expected, $value, $attributes); }

  /**
   * @covers Eloquent\Typhoon\Type\IntegerType::typhoonName
   * @group types
   */
  public function testTyphoonName() { parent::testTyphoonName(); }
}
