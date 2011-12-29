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
use Ezzatron\Typhoon\Primitive\String;

class CallbackWrapperTypeTest extends \Ezzatron\Typhoon\Test\TypeTestCase
{
  /**
   * @return array
   */
  public function typeValues()
  {
    $callback = function() {
      $arguments = func_get_args();
      array_shift($arguments);
      $return = true;

      foreach ($arguments as $argument) {
        $return = $return && $argument;
      }

      return $return;
    };

    $attributesPass = array(
      CallbackWrapperType::ATTRIBUTE_CALLBACK => $callback,
      CallbackWrapperType::ATTRIBUTE_ARGUMENTS => array(true, true),
    );
    $attributesFail = array(
      CallbackWrapperType::ATTRIBUTE_CALLBACK => $callback,
      CallbackWrapperType::ATTRIBUTE_ARGUMENTS => array(true, false),
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

      array(true,  null,  $attributesPass),  // #9: callback pass
      array(false, null,  $attributesFail),  // #10: callback fail
    );
  }

  /**
   * @return string
   */
  protected function typeClass()
  {
    return __NAMESPACE__.'\CallbackWrapperType';
  }

  /**
   * @covers Ezzatron\Typhoon\Type\CallbackWrapperType
   * @group types
   * @group type
   * @group dynamic-type
   */
  public function testCallbackWrapperType()
  {
    $called = false;
    $arguments = null;

    $callback = function() use(&$called, &$arguments)
    {
      $called = true;
      $arguments = func_get_args();
    };
    $attributes = array(
      CallbackWrapperType::ATTRIBUTE_CALLBACK => $callback,
      CallbackWrapperType::ATTRIBUTE_ARGUMENTS => array('bar', 'baz'),
    );
    $type = new CallbackWrapperType($attributes);

    $this->assertFalse($called);
    $this->assertNull($arguments);

    $type->typhoonCheck('foo');

    $this->assertTrue($called);
    $this->assertEquals(array('foo', 'bar', 'baz'), $arguments);
  }

  /**
   * @covers Ezzatron\Typhoon\Type\CallbackWrapperType::configureAttributeSignature
   * @group types
   * @group type
   * @group dynamic-type
   */
  public function testConfigureAttributeSignature()
  {
    $expected = new AttributeSignature;
    $expected->setHolderName(new String($this->typeClass()));
    $expected[CallbackWrapperType::ATTRIBUTE_CALLBACK] = new CallbackType;
    $expected[CallbackWrapperType::ATTRIBUTE_ARGUMENTS] = new ArrayType;

    $type = new CallbackWrapperType;

    $this->assertEquals($expected, $type->typhoonAttributes()->signature());
  }

  // methods below must be manually overridden to implement @covers

  /**
   * @covers Ezzatron\Typhoon\Type\CallbackWrapperType::typhoonCheck
   * @covers Ezzatron\Typhoon\Type\CallbackWrapperType::defaultCallback
   * @dataProvider typeValues
   * @group types
   * @group type
   * @group dynamic-type
   */
  public function testTyphoonCheck($expected, $value, $attributes = null) { parent::testTyphoonCheck($expected, $value, $attributes); }
}