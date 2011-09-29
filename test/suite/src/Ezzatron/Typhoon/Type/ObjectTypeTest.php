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
use Ezzatron\Typhoon\Attribute\Attributes;
use Ezzatron\Typhoon\Attribute\AttributeSignature;
use Ezzatron\Typhoon\Type\StringType;

class ObjectTypeTest extends \Ezzatron\Typhoon\Test\TypeTestCase
{
  /**
   * @return array
   */
  public function typeValues()
  {
    $attributes = new Attributes(array(ObjectType::ATTRIBUTE_INSTANCE_OF => 'stdClass'));

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
      array(true,  new stdClass,             $attributes),  // #9: object of correct class
      array(false, new ObjectType,           $attributes),  // #10: object of incorrect class
      array(false, null,                     $attributes),  // #11: null
      array(false, true,                     $attributes),  // #12: boolean
      array(false, 'string',                 $attributes),  // #13: string
      array(false, 1,                        $attributes),  // #14: integer
      array(false, .1,                       $attributes),  // #15: float
      array(false, array(),                  $attributes),  // #16: array
      array(false, function(){},             $attributes),  // #17: closure
      array(false, $this->resourceFixture(), $attributes),  // #18: resource
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
   * @covers Ezzatron\Typhoon\Type\ObjectType::configureAttributeSignature
   */
  public function testConfigureAttributeSignature()
  {
    $expected = new AttributeSignature;
    $expected->setHolder($this->typeClass());
    $expected[ObjectType::ATTRIBUTE_INSTANCE_OF] = new StringType;

    $object = new ObjectType;
    $actual = $object->typhoonAttributes()->signature();

    $this->assertEquals($expected, $actual);

    $object = new ObjectType;

    $this->assertEquals($actual, $object->typhoonAttributes()->signature());
  }

  /**
   * @covers Ezzatron\Typhoon\Type\ObjectType::typhoonAttributes
   */
  public function testSetTyphoonAttribute()
  {
    $type = $this->typeFixture();
    $type->typhoonAttributes()->set(ObjectType::ATTRIBUTE_INSTANCE_OF, 'foo');

    $this->assertEquals('foo', $type->typhoonAttributes()->get(ObjectType::ATTRIBUTE_INSTANCE_OF));
  }

  /**
   * @covers Ezzatron\Typhoon\Type\ObjectType::typhoonAttributes
   */
  public function testSetTyphoonAttributeFailure()
  {
    $type = $this->typeFixture();
    $this->setExpectedException('Ezzatron\Typhoon\Assertion\Exception\UnexpectedTypeException');
    $type->typhoonAttributes()->set(ObjectType::ATTRIBUTE_INSTANCE_OF, 1);
  }

  // methods below must be manually overridden to implement @covers
  
  /**
   * @covers Ezzatron\Typhoon\Type\ObjectType::typhoonCheck
   * @dataProvider typeValues
   * @group typhoon_types
   */
  public function testTyphoonCheck($expected, $value, Attributes $attributes = null) { parent::testTyphoonCheck($expected, $value, $attributes); }
}