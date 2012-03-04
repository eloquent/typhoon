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

class SetTypeTest extends \Eloquent\Typhoon\Test\TypeTestCase
{
  /**
   * @return array
   */
  public function typeValues()
  {
    $attributes = array(
      SetType::ATTRIBUTE_CLASS => 'Eloquent\Typhoon\Test\Fixture\Enumeration',
    );

    return array(
      array(true,  array(),                                                     $attributes), // #0: set values success
      array(true,  array(Enumeration::FOO),                                     $attributes), // #1: set values success
      array(true,  array(Enumeration::FOO, Enumeration::BAR),                   $attributes), // #2: set values success
      array(true,  array(Enumeration::FOO, Enumeration::BAR, Enumeration::BAZ), $attributes), // #3: set values success

      array(false, new stdClass,             $attributes),  // #4: object
      array(false, null,                     $attributes),  // #5: null
      array(false, true,                     $attributes),  // #6: boolean
      array(false, 'string',                 $attributes),  // #7: string
      array(false, 1,                        $attributes),  // #8: integer
      array(false, .1,                       $attributes),  // #9: float
      array(false, function(){},             $attributes),  // #10: closure
      array(false, $this->resourceFixture(), $attributes),  // #11: resource

      array(false, array('doom'),                   $attributes),  // #12: array with non-set-matching values
      array(false, array(Enumeration::FOO, 'doom'), $attributes),  // #13: array with mixed matching and non-matching values
    );
  }

  /**
   * @return string
   */
  protected function typeClass()
  {
    return __NAMESPACE__.'\SetType';
  }

  /**
   * @covers Eloquent\Typhoon\Type\SetType::configureAttributeSignature
   * @group types
   * @group type
   * @group dynamic-type
   */
  public function testConfigureAttributeSignature()
  {
    $expected = new AttributeSignature;
    $expected->setHolderName(new String($this->typeClass()));
    $expected->set(SetType::ATTRIBUTE_CLASS, new ClassType(array(
      ClassType::ATTRIBUTE_INSTANCE_OF => 'Eloquent\Typhoon\Enumeration\Enumeration',
    )), new Boolean(true));

    $type = new SetType(array(
      SetType::ATTRIBUTE_CLASS => 'Eloquent\Typhoon\Test\Fixture\Enumeration',
    ));

    $this->assertEquals($expected, $type->typhoonAttributes()->signature());
  }

  // methods below must be manually overridden to implement @covers

  /**
   * @covers Eloquent\Typhoon\Type\SetType::__construct
   * @covers Eloquent\Typhoon\Type\SetType::typhoonCheck
   * @dataProvider typeValues
   * @group types
   * @group type
   * @group dynamic-type
   */
  public function testTyphoonCheck($expected, $value, $attributes = null) { parent::testTyphoonCheck($expected, $value, $attributes); }
}
