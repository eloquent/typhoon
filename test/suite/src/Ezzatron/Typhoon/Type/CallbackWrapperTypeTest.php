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
    $expected->set(CallbackWrapperType::ATTRIBUTE_CALLBACK, new CallbackType, new Boolean(true));
    $expected->set(CallbackWrapperType::ATTRIBUTE_ARGUMENTS, new ArrayType);

    $type = new CallbackWrapperType(array(
      'callback' => 'is_int'
    ));

    $this->assertEquals($expected, $type->typhoonAttributes()->signature());
  }

  // methods below must be manually overridden to implement @covers

  /**
   * @covers Ezzatron\Typhoon\Type\CallbackWrapperType::typhoonCheck
   * @dataProvider typeValues
   * @group types
   * @group type
   * @group dynamic-type
   */
  public function testTyphoonCheck($expected, $value, $attributes = null) { parent::testTyphoonCheck($expected, $value, $attributes); }
}
