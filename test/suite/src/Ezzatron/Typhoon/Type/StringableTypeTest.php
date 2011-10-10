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

class StringableTypeTest extends \Ezzatron\Typhoon\Test\TypeTestCase
{
  /**
   * @return array
   */
  public function typeValues()
  {
    $utf8Attributes = new Attributes(array(
      StringableType::ATTRIBUTE_ENCODING => 'UTF-8',
    ));

    return array(
      array(false, null),                      // #0: null
      array(true,  true),                      // #1: boolean
      array(true,  'string'),                  // #2: string
      array(true,  1),                         // #3: integer
      array(true,  .1),                        // #4: float
      array(false, array()),                   // #5: array
      array(false, new stdClass),              // #6: object
      array(false, function(){}),              // #7: closure
      array(false, $this->resourceFixture()),  // #8: resource

      array(true,  $this->stringableFixture()),  // #10: stringable object

      array(true,  chr(127), $utf8Attributes),   // #11: string with specific encoding success
      array(false, chr(128), $utf8Attributes),   // #12: string with specific encoding failure

      // #13: stringable object with specific encoding success
      array(true,  $this->stringableFixture(chr(127)), $utf8Attributes),
      // #14: stringable object with specific encoding failure
      array(false, $this->stringableFixture(chr(128)), $utf8Attributes),
    );
  }
  
  /**
   * @return string
   */
  protected function typeClass()
  {
    return __NAMESPACE__.'\StringableType';
  }
  
  /**
   * @covers Ezzatron\Typhoon\Type\StringableType::typhoonCheck
   * @dataProvider typeValues
   */
  public function testStringConversion($expected, $value, Attributes $attributes = null)
  {
    if ($expected)
    {
      (string)$value;
    }
    
    $this->assertTrue(true);
  }

  /**
   * @covers Ezzatron\Typhoon\Type\StringableType::configureAttributeSignature
   */
  public function testConfigureAttributeSignature()
  {
    $expected = new AttributeSignature;
    $expected->setHolderName(new String($this->typeClass()));
    $expected[StringableType::ATTRIBUTE_ENCODING] = new StringType;

    $type = new StringableType;
    $actual = $type->typhoonAttributes()->signature();

    $this->assertEquals($expected, $actual);

    $type = new StringableType;

    $this->assertEquals($actual, $type->typhoonAttributes()->signature());
  }

  // methods below must be manually overridden to implement @covers

  /**
   * @covers Ezzatron\Typhoon\Type\StringableType::__construct
   * @covers Ezzatron\Typhoon\Type\StringableType::typhoonCheck
   * @dataProvider typeValues
   * @group typhoon_types
   */
  public function testTyphoonCheck($expected, $value, Attributes $attributes = null) { parent::testTyphoonCheck($expected, $value, $attributes); }
}