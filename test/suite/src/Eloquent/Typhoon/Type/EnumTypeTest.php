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
use Eloquent\Typhoon\Primitive\Boolean;
use Eloquent\Typhoon\Primitive\String;
use Eloquent\Typhoon\Test\Fixture\Enumeration;

class EnumerationTypeTest extends \Eloquent\Typhoon\Test\TypeTestCase
{
  /**
   * @return array
   */
  public function typeValues()
  {
    $attributes = array(
      EnumerationType::ATTRIBUTE_CLASS => 'Eloquent\Typhoon\Test\Fixture\Enumeration',
    );

    return array(
      array(true,  Enumeration::FOO,                $attributes), // #0: enumeration value success
      array(true,  Enumeration::BAR,                $attributes), // #1: enumeration value success
      array(true,  Enumeration::BAZ,                $attributes), // #2: enumeration value success

      array(false, new stdClass,             $attributes),  // #3: object
      array(false, null,                     $attributes),  // #4: null
      array(false, true,                     $attributes),  // #5: boolean
      array(false, 'string',                 $attributes),  // #6: string
      array(false, 1,                        $attributes),  // #7: integer
      array(false, .1,                       $attributes),  // #8: float
      array(false, array(),                  $attributes),  // #9: array
      array(false, function(){},             $attributes),  // #10: closure
      array(false, $this->resourceFixture(), $attributes),  // #11: resource
    );
  }

  /**
   * @return string
   */
  protected function typeClass()
  {
    return __NAMESPACE__.'\EnumerationType';
  }

  /**
   * @covers Eloquent\Typhoon\Type\EnumerationType::configureAttributeSignature
   * @group types
   * @group type
   * @group dynamic-type
   */
  public function testConfigureAttributeSignature()
  {
    $expected = new AttributeSignature;
    $expected->setHolderName(new String($this->typeClass()));
    $expected->set(EnumerationType::ATTRIBUTE_CLASS, new ClassType(array(
      ClassType::ATTRIBUTE_INSTANCE_OF => 'Eloquent\Typhoon\Enumeration\Enumeration',
    )), new Boolean(true));

    $type = new EnumerationType(array(
      EnumerationType::ATTRIBUTE_CLASS => 'Eloquent\Typhoon\Test\Fixture\Enumeration',
    ));

    $this->assertEquals($expected, $type->typhoonAttributes()->signature());
  }

  // methods below must be manually overridden to implement @covers

  /**
   * @covers Eloquent\Typhoon\Type\EnumerationType::typhoonCheck
   * @dataProvider typeValues
   * @group types
   * @group type
   * @group dynamic-type
   */
  public function testTyphoonCheck($expected, $value, $attributes = null) { parent::testTyphoonCheck($expected, $value, $attributes); }
}
