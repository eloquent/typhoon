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
use Ezzatron\Typhoon\Primitive\String;
use Ezzatron\Typhoon\Type\CallbackType;
use Ezzatron\Typhoon\Type\ArrayType;

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

    $attributesPass = new Attributes(array(
      CallbackWrapperType::ATTRIBUTE_CALLBACK => $callback,
      CallbackWrapperType::ATTRIBUTE_ARGUMENTS => array(true, true),
    ));
    $attributesFail = new Attributes(array(
      CallbackWrapperType::ATTRIBUTE_CALLBACK => $callback,
      CallbackWrapperType::ATTRIBUTE_ARGUMENTS => array(true, false),
    ));

    return array(
      // object of any class
      array(false, null),                      // #0: null
      array(false, true),                      // #1: boolean
      array(false, 'string'),                  // #2: string
      array(false, 1),                         // #3: integer
      array(false, .1),                        // #4: float
      array(false, array()),                   // #5: array
      array(false, new stdClass),              // #6: object
      array(false, function(){}),              // #7: closure
      array(false, $this->resourceFixture()),  // #8: resource

      // object of a specific class
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
    $attributes = new Attributes(array(
      CallbackWrapperType::ATTRIBUTE_CALLBACK => $callback,
      CallbackWrapperType::ATTRIBUTE_ARGUMENTS => array('bar', 'baz'),
    ));
    $type = new CallbackWrapperType($attributes);

    $this->assertFalse($called);
    $this->assertNull($arguments);

    $type->typhoonCheck('foo');

    $this->assertTrue($called);
    $this->assertEquals(array('foo', 'bar', 'baz'), $arguments);
  }

  /**
   * @covers Ezzatron\Typhoon\Type\CallbackWrapperType::configureAttributeSignature
   */
  public function testConfigureAttributeSignature()
  {
    $expected = new AttributeSignature;
    $expected->setHolderName(new String($this->typeClass()));
    $expected[CallbackWrapperType::ATTRIBUTE_CALLBACK] = new CallbackType;
    $expected[CallbackWrapperType::ATTRIBUTE_ARGUMENTS] = new ArrayType;

    $type = new CallbackWrapperType;
    $actual = $type->typhoonAttributes()->signature();

    $this->assertEquals($expected, $actual);

    $type = new CallbackWrapperType;

    $this->assertEquals($actual, $type->typhoonAttributes()->signature());
  }

  // methods below must be manually overridden to implement @covers

  /**
   * @covers Ezzatron\Typhoon\Type\CallbackWrapperType::typhoonCheck
   * @covers Ezzatron\Typhoon\Type\CallbackWrapperType::defaultCallback
   * @dataProvider typeValues
   * @group typhoon_types
   */
  public function testTyphoonCheck($expected, $value, Attributes $attributes = null) { parent::testTyphoonCheck($expected, $value, $attributes); }
}