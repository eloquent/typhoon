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
use Eloquent\Typhoon\Type\StringType;
use stdClass;

class ObjectTypeTest extends \Eloquent\Typhoon\Test\TypeTestCase
{
  /**
   * @return array
   */
  public function typeValues()
  {
    $stdClassAttributes = array(ObjectType::ATTRIBUTE_INSTANCE_OF => 'stdClass');

    $dynamicAndSubTypedTypeAttributes = array(ObjectType::ATTRIBUTE_INSTANCE_OF => array(
      __NAMESPACE__.'\Dynamic\DynamicType',
      __NAMESPACE__.'\SubTyped\SubTypedType',
    ));

    return array(
      // object of any class
      array(false, null),                      // #0: null
      array(false, true),                      // #1: boolean
      array(false, 'string'),                  // #2: string
      array(false, 1),                         // #3: integer
      array(false, .1),                        // #4: float
      array(false, array()),                   // #5: array
      array(true,  new stdClass),              // #6: object
      array(true,  function(){}),              // #7: closure
      array(false, $this->resourceFixture()),  // #8: resource

      // object of a specific class
      array(true,  new stdClass,             $stdClassAttributes),  // #9: object of correct class
      array(false, new ObjectType,           $stdClassAttributes),  // #10: object of incorrect class
      array(false, null,                     $stdClassAttributes),  // #11: null
      array(false, true,                     $stdClassAttributes),  // #12: boolean
      array(false, 'string',                 $stdClassAttributes),  // #13: string
      array(false, 1,                        $stdClassAttributes),  // #14: integer
      array(false, .1,                       $stdClassAttributes),  // #15: float
      array(false, array(),                  $stdClassAttributes),  // #16: array
      array(false, function(){},             $stdClassAttributes),  // #17: closure
      array(false, $this->resourceFixture(), $stdClassAttributes),  // #18: resource

      array(true,  new TraversableType,      $dynamicAndSubTypedTypeAttributes),  // #19: object of two simultaneous types success
      array(false, new StringType,           $dynamicAndSubTypedTypeAttributes),  // #20: object of two simultaneous types partial failure
      array(false, new stdClass,             $dynamicAndSubTypedTypeAttributes),  // #21: object of two simultaneous types complete failure
    );
  }

  /**
   * @return string
   */
  protected function typeClass()
  {
    return __NAMESPACE__.'\ObjectType';
  }

  /**
   * @return string
   */
  protected function typeName()
  {
    return IntrinsicTypeName::NAME_OBJECT()->_value();
  }

  /**
   * @covers Eloquent\Typhoon\Type\ObjectType::configureAttributeSignature
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
    $expected->set(ObjectType::ATTRIBUTE_INSTANCE_OF, $stringOrArrayOfStringType);

    $type = new ObjectType;

    $this->assertEquals($expected, $type->typhoonAttributes()->signature());
  }

  // methods below must be manually overridden to implement @covers

  /**
   * @covers Eloquent\Typhoon\Type\ObjectType::typhoonCheck
   * @dataProvider typeValues
   * @group types
   * @group type
   * @group dynamic-type
   */
  public function testTyphoonCheck($expected, $value, $attributes = null) { parent::testTyphoonCheck($expected, $value, $attributes); }

  /**
   * @covers Eloquent\Typhoon\Type\ObjectType::typhoonName
   * @group types
   */
  public function testTyphoonName() { parent::testTyphoonName(); }
}
