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

use stdClass;
use Eloquent\Typhoon\Attribute\AttributeSignature;
use Eloquent\Typhoon\Primitive\String;

class InterfaceTypeTest extends \Eloquent\Typhoon\Test\TypeTestCase
{
  /**
   * @return array
   */
  public function typeValues()
  {
    $typeInterfaceAttributes = array(
      InterfaceType::ATTRIBUTE_IMPLEMENTS => __NAMESPACE__.'\Type',
    );

    $typeAndSubTypedTypeInterfaceAttributes = array(
      InterfaceType::ATTRIBUTE_IMPLEMENTS => array(
        __NAMESPACE__.'\Type',
        __NAMESPACE__.'\SubTyped\SubTypedType',
      ),
    );

    return array(
      array(false, null),                      // #0: null
      array(false, true),                      // #1: boolean
      array(false, 'string'),                  // #2: string
      array(false, 1),                         // #3: integer
      array(false, .1),                        // #4: float
      array(false, array()),                   // #5: array
      array(false, new stdClass),              // #6: object
      array(false, function(){}),              // #7: closure
      array(false, $this->resourceFixture()),  // #8: resource

      array(true,  'Iterator'),                      // #9: interface name
      array(true,  __NAMESPACE__.'\Type'),           // #10: interface name

      array(false, __NAMESPACE__.'\InterfaceType'),  // #11: class name

      array(true,  __NAMESPACE__.'\Dynamic\DynamicType', $typeInterfaceAttributes),  // #12: interface name of specific inheritance success
      array(true,  __NAMESPACE__.'\Type',                $typeInterfaceAttributes),  // #13: interface name of exact match success
      array(false, 'Iterator',                           $typeInterfaceAttributes),  // #14: interface name of specific inheritance failure

      array(true,  __NAMESPACE__.'\SubTyped\TraversableType', $typeAndSubTypedTypeInterfaceAttributes),  // #15: interface name of two simultaneous inheritances success
      array(false, __NAMESPACE__.'\Dynamic\DynamicType',      $typeAndSubTypedTypeInterfaceAttributes),  // #16: interface name of two simultaneous inheritances partial failure
      array(false, 'Iterator',                                $typeAndSubTypedTypeInterfaceAttributes),  // #17: interface name of two simultaneous inheritances complete failure
    );
  }

  /**
   * @return string
   */
  protected function typeClass()
  {
    return __NAMESPACE__.'\InterfaceType';
  }

  /**
   * @covers Eloquent\Typhoon\Type\InterfaceType
   * @covers Eloquent\Typhoon\Type\BaseClassType
   * @group class-types
   * @group types
   * @group type
   * @group dynamic-type
   */
  public function testConfigureAttributeSignature()
  {
    $arrayOfInterfaceType = new ArrayType;
    $arrayOfInterfaceType->setTyphoonSubType(new InterfaceType);
    $interfaceOrArrayOfInterfaceType = new Composite\OrType;
    $interfaceOrArrayOfInterfaceType->addTyphoonType(new InterfaceType);
    $interfaceOrArrayOfInterfaceType->addTyphoonType($arrayOfInterfaceType);

    $expected = new AttributeSignature;
    $expected->setHolderName(new String($this->typeClass()));
    $expected->set(InterfaceType::ATTRIBUTE_IMPLEMENTS, $interfaceOrArrayOfInterfaceType);

    $type = new InterfaceType;

    $this->assertEquals($expected, $type->typhoonAttributes()->signature());
  }

  // methods below must be manually overridden to implement @covers

  /**
   * @covers Eloquent\Typhoon\Type\InterfaceType
   * @covers Eloquent\Typhoon\Type\BaseClassType
   * @dataProvider typeValues
   * @group class-types
   * @group types
   * @group type
   * @group dynamic-type
   */
  public function testTyphoonCheck($expected, $value, $attributes = null) { parent::testTyphoonCheck($expected, $value, $attributes); }
}
