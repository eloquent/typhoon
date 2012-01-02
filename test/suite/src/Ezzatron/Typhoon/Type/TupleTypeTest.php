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

use stdClass;
use Ezzatron\Typhoon\Attribute\AttributeSignature;
use Ezzatron\Typhoon\Primitive\Boolean;
use Ezzatron\Typhoon\Primitive\String;

class TupleTypeTest extends \Ezzatron\Typhoon\Test\TypeTestCase
{
  /**
   * @return array
   */
  public function typeValues()
  {
    $attributes = array(
      TupleType::ATTRIBUTE_TYPES => array(
        new StringType,
        new IntegerType,
        new BooleanType,
      ),
    );

    return array(
      array(false, null,                     $attributes),  // #0: null
      array(false, true,                     $attributes),  // #1: boolean
      array(false, 'string',                 $attributes),  // #2: string
      array(false, 1,                        $attributes),  // #3: integer
      array(false, .1,                       $attributes),  // #4: float
      array(false, array(),                  $attributes),  // #5: array
      array(false, new stdClass,             $attributes),  // #6: object
      array(false, function(){},             $attributes),  // #7: closure
      array(false, $this->resourceFixture(), $attributes),  // #8: resource

      array(true,  array('foo', 1, true),    $attributes),  // #9: correct set of values
      array(true,  array('bar', 666, false), $attributes),  // #10: correct set of values

      array(false, array(1 => 'foo', 2 => 1, 3 => true), $attributes),  // #11: correct set of values, but non-sequential

      array(false, array('foo', 1, 'foo'), $attributes),  // #12: incorrect set of values
      array(false, array(1, 1, true),      $attributes),  // #13: incorrect set of values
    );
  }

  /**
   * @return string
   */
  protected function typeClass()
  {
    return __NAMESPACE__.'\TupleType';
  }

  /**
   * @covers Ezzatron\Typhoon\Type\TupleType::configureAttributeSignature
   * @group types
   * @group type
   * @group dynamic-type
   */
  public function testConfigureAttributeSignature()
  {
    $arrayOfTypesType = new ArrayType;
    $arrayOfTypesType->setTyphoonKeyType(new IntegerType);
    $arrayOfTypesType->setTyphoonSubType(new TypeType);

    $expected = new AttributeSignature;
    $expected->setHolderName(new String($this->typeClass()));
    $expected->set(TupleType::ATTRIBUTE_TYPES, $arrayOfTypesType, new Boolean(true));

    $type = new TupleType(array(
      TupleType::ATTRIBUTE_TYPES => array(),
    ));

    $this->assertEquals($expected, $type->typhoonAttributes()->signature());
  }

  // methods below must be manually overridden to implement @covers

  /**
   * @covers Ezzatron\Typhoon\Type\TupleType::__construct
   * @covers Ezzatron\Typhoon\Type\TupleType::typhoonCheck
   * @dataProvider typeValues
   * @group types
   * @group type
   * @group dynamic-type
   */
  public function testTyphoonCheck($expected, $value, $attributes = null) { parent::testTyphoonCheck($expected, $value, $attributes); }
}
