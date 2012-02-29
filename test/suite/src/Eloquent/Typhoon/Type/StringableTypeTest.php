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
use Eloquent\Typhoon\Primitive\String;

class StringableTypeTest extends \Eloquent\Typhoon\Test\TypeTestCase
{
  /**
   * @return array
   */
  public function typeValues()
  {
    $utf8Attributes = array(
      StringableType::ATTRIBUTE_ENCODING => 'UTF-8',
    );

    $utf8OrUtf32Attributes = array(
      StringableType::ATTRIBUTE_ENCODING => array(
        'UTF-8',
        'UTF-32',
      ),
    );

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

      // #15: stringable object with one of two encodings success
      array(true,  $this->stringableFixture(chr(127)), $utf8OrUtf32Attributes),
      // #16: stringable object with one of two encodings success
      array(true,  $this->stringableFixture(mb_convert_encoding(chr(127), 'UTF-32', 'UTF-8')), $utf8OrUtf32Attributes),
      // #17: stringable object with one of two encodings failure
      array(false, $this->stringableFixture(chr(128)), $utf8OrUtf32Attributes),
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
   * @covers Eloquent\Typhoon\Type\StringableType::typhoonCheck
   * @dataProvider typeValues
   * @group types
   * @group type
   * @group dynamic-type
   */
  public function testStringConversion($expected, $value, $attributes = null)
  {
    if ($expected)
    {
      (string)$value;
    }
    
    $this->assertTrue(true);
  }

  /**
   * @covers Eloquent\Typhoon\Type\StringableType::configureAttributeSignature
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
    $expected->set(StringableType::ATTRIBUTE_ENCODING, $stringOrArrayOfStringType);

    $type = new StringableType;

    $this->assertEquals($expected, $type->typhoonAttributes()->signature());
  }

  // methods below must be manually overridden to implement @covers

  /**
   * @covers Eloquent\Typhoon\Type\StringableType::__construct
   * @covers Eloquent\Typhoon\Type\StringableType::typhoonCheck
   * @dataProvider typeValues
   * @group types
   * @group type
   * @group dynamic-type
   */
  public function testTyphoonCheck($expected, $value, $attributes = null) { parent::testTyphoonCheck($expected, $value, $attributes); }
}
