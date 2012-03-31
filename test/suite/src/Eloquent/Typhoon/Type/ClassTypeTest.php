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

class ClassTypeTest extends \Eloquent\Typhoon\Test\TypeTestCase
{
  /**
   * @return array
   */
  public function typeValues()
  {
    $baseTypeClassAttributes = array(
      ClassType::ATTRIBUTE_EXTENDS => __NAMESPACE__.'\BaseType',
    );

    $typeClassAttributes = array(
      ClassType::ATTRIBUTE_IMPLEMENTS => __NAMESPACE__.'\Type',
    );

    $dynamicAndSubTypedTypeClassAttributes = array(
      ClassType::ATTRIBUTE_IMPLEMENTS => array(
        __NAMESPACE__.'\Dynamic\DynamicType',
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

      array(true,  __CLASS__),                   // #9: class name
      array(true,  __NAMESPACE__.'\ClassType'),  // #10: class name

      array(false, __NAMESPACE__.'\Type'),       // #11: interface name

      array(true,  __NAMESPACE__.'\ClassType', $baseTypeClassAttributes),  // #12: class name of specific class inheritance success
      array(false, __CLASS__,                  $baseTypeClassAttributes),  // #13: class name of specific class inheritance failure

      array(true,  __NAMESPACE__.'\ClassType', $typeClassAttributes),  // #14: class name of specific interface inheritance success
      array(false, __CLASS__,                  $typeClassAttributes),  // #15: class name of specific interface inheritance failure

      array(true,  __NAMESPACE__.'\TraversableType', $dynamicAndSubTypedTypeClassAttributes),  // #16: class name of two simultaneous interface inheritances success
      array(false, __NAMESPACE__.'\StringType',      $dynamicAndSubTypedTypeClassAttributes),  // #17: class name of two simultaneous interface inheritances partial failure
      array(false, 'stdClass',                       $dynamicAndSubTypedTypeClassAttributes),  // #18: class name of two simultaneous interface inheritances complete failure
    );
  }

  /**
   * @return string
   */
  protected function typeClass()
  {
    return __NAMESPACE__.'\ClassType';
  }

  /**
   * @covers Eloquent\Typhoon\Type\ClassType::configureAttributeSignature
   * @covers Eloquent\Typhoon\Type\BaseClassType
   * @group class-types
   * @group types
   * @group type
   * @group dynamic-type
   */
  public function testConfigureAttributeSignature()
  {
    $arrayOfStringType = new ArrayType;
    $arrayOfStringType->setTyphoonSubType(new StringType);
    $stringOrArrayOfStringType = new Composite\OrType;
    $stringOrArrayOfStringType->addTyphoonType(new StringType);
    $stringOrArrayOfStringType->addTyphoonType($arrayOfStringType);

    $expected = new AttributeSignature;
    $expected->setHolderName(new String($this->typeClass()));
    $expected->set(ClassType::ATTRIBUTE_EXTENDS, new StringType);
    $expected->set(ClassType::ATTRIBUTE_IMPLEMENTS, $stringOrArrayOfStringType);

    $type = new ClassType;

    $this->assertEquals($expected, $type->typhoonAttributes()->signature());
  }

  // methods below must be manually overridden to implement @covers

  /**
   * @covers Eloquent\Typhoon\Type\ClassType
   * @covers Eloquent\Typhoon\Type\BaseClassType
   * @dataProvider typeValues
   * @group class-types
   * @group types
   * @group type
   * @group dynamic-type
   */
  public function testTyphoonCheck($expected, $value, $attributes = null) { parent::testTyphoonCheck($expected, $value, $attributes); }
}
