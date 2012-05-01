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
use Eloquent\Typhoon\Attribute\AttributeSignature;
use Eloquent\Typhoon\Primitive\String;
use stdClass;

class ClassNameTypeTest extends \Eloquent\Typhoon\Test\TypeTestCase
{
  /**
   * @return array
   */
  public function typeValues()
  {
    $baseDynamicTypeClassAttributes = array(
      ClassNameType::ATTRIBUTE_CLASS_OF => __NAMESPACE__.'\Dynamic\BaseDynamicType',
    );

    $typeClassAttributes = array(
      ClassNameType::ATTRIBUTE_IMPLEMENTS => __NAMESPACE__.'\Type',
    );

    $dynamicAndSubTypedTypeClassAttributes = array(
      ClassNameType::ATTRIBUTE_IMPLEMENTS => array(
        __NAMESPACE__.'\Dynamic\DynamicType',
        __NAMESPACE__.'\SubTyped\SubTypedType',
      ),
    );

    $instantiableTypeClassAttributes = array(
      ClassNameType::ATTRIBUTE_IMPLEMENTS => __NAMESPACE__.'\Type',
      ClassNameType::ATTRIBUTE_INSTANTIABLE => true,
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

      array(true,  __CLASS__),                       // #9: class name
      array(true,  __NAMESPACE__.'\ClassNameType'),  // #10: class name

      array(false, __NAMESPACE__.'\Type'),           // #11: interface name

      array(true,  __NAMESPACE__.'\StringType',                      $baseDynamicTypeClassAttributes),  // #12: class name of specific class inheritance success
      array(true,  __NAMESPACE__.'\Dynamic\BaseDynamicType',         $baseDynamicTypeClassAttributes),  // #13: class name of exact match success - no leading slash
      array(true,  '\\' . __NAMESPACE__.'\Dynamic\BaseDynamicType',  $baseDynamicTypeClassAttributes),  // #14: class name of exact match success - leading slash
      array(false, __CLASS__,                                        $baseDynamicTypeClassAttributes),  // #15: class name of specific class inheritance failure

      array(true,  __NAMESPACE__.'\ClassNameType',    $typeClassAttributes),  // #16: class name of specific interface inheritance success
      array(false, __CLASS__,                         $typeClassAttributes),  // #17: class name of specific interface inheritance failure

      array(true,  __NAMESPACE__.'\TraversableType',  $dynamicAndSubTypedTypeClassAttributes),  // #18: class name of two simultaneous interface inheritances success
      array(false, __NAMESPACE__.'\StringType',       $dynamicAndSubTypedTypeClassAttributes),  // #19: class name of two simultaneous interface inheritances partial failure
      array(false, 'stdClass',                        $dynamicAndSubTypedTypeClassAttributes),  // #20: class name of two simultaneous interface inheritances complete failure

      array(true,  __NAMESPACE__.'\StringType',               $instantiableTypeClassAttributes),  // #21: instantiable type class success
      array(false, __NAMESPACE__.'\Dynamic\BaseDynamicType',  $instantiableTypeClassAttributes),  // #22: instantiable type class failure
    );
  }

  /**
   * @return string
   */
  protected function typeClass()
  {
    return __NAMESPACE__.'\ClassNameType';
  }

  /**
   * @return string
   */
  protected function typeName()
  {
    return IntrinsicTypeName::NAME_CLASS_NAME()->_value();
  }

  /**
   * @covers Eloquent\Typhoon\Type\ClassNameType::configureAttributeSignature
   * @covers Eloquent\Typhoon\Type\BaseClassNameType
   * @group class-types
   * @group types
   * @group type
   * @group dynamic-type
   */
  public function testConfigureAttributeSignature()
  {
    $arrayOfInterfaceType = new ArrayType;
    $arrayOfInterfaceType->setTyphoonSubType(new InterfaceNameType);
    $interfaceOrArrayOfInterfaceType = new Composite\OrType;
    $interfaceOrArrayOfInterfaceType->addTyphoonType(new InterfaceNameType);
    $interfaceOrArrayOfInterfaceType->addTyphoonType($arrayOfInterfaceType);

    $expected = new AttributeSignature;
    $expected->setHolderName(new String($this->typeClass()));
    $expected->set(ClassNameType::ATTRIBUTE_INSTANTIABLE, new BooleanType);
    $expected->set(ClassNameType::ATTRIBUTE_CLASS_OF, new ClassNameType);
    $expected->set(ClassNameType::ATTRIBUTE_IMPLEMENTS, $interfaceOrArrayOfInterfaceType);

    $type = new ClassNameType;

    $this->assertEquals($expected, $type->typhoonAttributes()->signature());
  }

  // methods below must be manually overridden to implement @covers

  /**
   * @covers Eloquent\Typhoon\Type\ClassNameType
   * @covers Eloquent\Typhoon\Type\BaseClassNameType
   * @dataProvider typeValues
   * @group class-types
   * @group types
   * @group type
   * @group dynamic-type
   */
  public function testTyphoonCheck($expected, $value, $attributes = null) { parent::testTyphoonCheck($expected, $value, $attributes); }

  /**
   * @covers Eloquent\Typhoon\Type\ClassNameType::typhoonName
   * @group types
   */
  public function testTyphoonName() { parent::testTyphoonName(); }
}
