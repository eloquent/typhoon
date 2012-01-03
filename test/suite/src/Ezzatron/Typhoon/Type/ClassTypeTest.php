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

class ClassTypeTest extends \Ezzatron\Typhoon\Test\TypeTestCase
{
  /**
   * @return array
   */
  public function typeValues()
  {
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
   * @covers Ezzatron\Typhoon\Type\ClassType::typhoonCheck
   * @group types
   * @group type
   * @group dynamic-type
   */
  public function testAutoload()
  {
    $autoloaded = array();
    $autoload = function($name) use(&$autoloaded) {
      $autoloaded[] = $name;
    };
    spl_autoload_register($autoload);

    $classTypeDefault = $this->typeFixture();
    $classTypeTrue = $this->typeFixture(array(
      ClassType::ATTRIBUTE_AUTOLOAD => true,
    ));
    $classTypeFalse = $this->typeFixture(array(
      ClassType::ATTRIBUTE_AUTOLOAD => false,
    ));

    $classTypeDefault->typhoonCheck($first = uniqid());
    $classTypeTrue->typhoonCheck($second = uniqid());
    $classTypeFalse->typhoonCheck($third = uniqid());

    spl_autoload_register($autoload);

    $this->assertSame(array($first, $second), $autoloaded);
  }

  /**
   * @covers Ezzatron\Typhoon\Type\ClassType::configureAttributeSignature
   * @group types
   * @group type
   * @group dynamic-type
   */
  public function testConfigureAttributeSignature()
  {
    $expected = new AttributeSignature;
    $expected->setHolderName(new String($this->typeClass()));
    $expected->set(ClassType::ATTRIBUTE_AUTOLOAD, new BooleanType);

    $type = new ClassType;

    $this->assertEquals($expected, $type->typhoonAttributes()->signature());
  }

  // methods below must be manually overridden to implement @covers

  /**
   * @covers Ezzatron\Typhoon\Type\ClassType::__construct
   * @covers Ezzatron\Typhoon\Type\ClassType::typhoonCheck
   * @dataProvider typeValues
   * @group types
   * @group type
   * @group dynamic-type
   */
  public function testTyphoonCheck($expected, $value, $attributes = null) { parent::testTyphoonCheck($expected, $value, $attributes); }
}
