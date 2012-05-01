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

class MixedTypeTest extends \Eloquent\Typhoon\Test\TypeTestCase
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
    return __NAMESPACE__.'\MixedType';
  }

  /**
   * @return string
   */
  protected function typeName()
  {
    return IntrinsicTypeName::NAME_MIXED()->_value();
  }

  // methods below must be manually overridden to implement @covers

  /**
   * @covers Eloquent\Typhoon\Type\MixedType::typhoonCheck
   * @dataProvider typeValues
   * @group types
   * @group type
   * @group core
   */
  public function testTyphoonCheck($expected, $value, $attributes = null) { parent::testTyphoonCheck($expected, $value, $attributes); }

  /**
   * @covers Eloquent\Typhoon\Type\MixedType::typhoonName
   * @group types
   */
  public function testTyphoonName() { parent::testTyphoonName(); }
}
