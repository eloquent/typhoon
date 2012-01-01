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

class StringTypeTest extends \Ezzatron\Typhoon\Test\TypeTestCase
{
  /**
   * @return array
   */
  public function typeValues()
  {
    $utf8Attributes = array(
      StringType::ATTRIBUTE_ENCODING => 'UTF-8',
    );

    $utf8OrUtf32Attributes = array(
      StringType::ATTRIBUTE_ENCODING => array(
        'UTF-8',
        'UTF-32',
      ),
    );

    return array(
      array(false, null),                      // #0: null
      array(false, true),                      // #1: boolean
      array(true,  'string'),                  // #2: string
      array(false, 1),                         // #3: integer
      array(false, .1),                        // #4: float
      array(false, array()),                   // #5: array
      array(false, new stdClass),              // #6: object
      array(false, function(){}),              // #7: closure
      array(false, $this->resourceFixture()),  // #8: resource

      array(true,  chr(127), $utf8Attributes),  // #9: string with specific encoding success
      array(false, chr(128), $utf8Attributes),  // #10: string with specific encoding failure

      // #11: string with one of two encodings success
      array(true,  chr(127), $utf8OrUtf32Attributes),
      // #12: string with one of two encodings success
      array(true,  mb_convert_encoding(chr(127), 'UTF-32', 'UTF-8'), $utf8OrUtf32Attributes),
      // #13: string with one of two encodings failure
      array(false, chr(128), $utf8OrUtf32Attributes),
    );
  }
  
  /**
   * @return string
   */
  protected function typeClass()
  {
    return __NAMESPACE__.'\StringType';
  }

  /**
   * @covers Ezzatron\Typhoon\Type\StringType::configureAttributeSignature
   * @group types
   * @group type
   * @group dynamic-type
   * @group core
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
    $expected->set(StringType::ATTRIBUTE_ENCODING, $stringOrArrayOfStringType);

    $type = new StringType;

    $this->assertEquals($expected, $type->typhoonAttributes()->signature());
  }

  // methods below must be manually overridden to implement @covers

  /**
   * @covers Ezzatron\Typhoon\Type\StringType::__construct
   * @covers Ezzatron\Typhoon\Type\StringType::typhoonCheck
   * @dataProvider typeValues
   * @group types
   * @group type
   * @group dynamic-type
   * @group core
   */
  public function testTyphoonCheck($expected, $value, $attributes = null) { parent::testTyphoonCheck($expected, $value, $attributes); }
}
