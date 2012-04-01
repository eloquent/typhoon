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

use Eloquent\Typhax\IntrinsicType\IntrinsicTypeName;
use Eloquent\Typhoon\Attribute\AttributeSignature;
use Eloquent\Typhoon\Primitive\String;
use stdClass;

class CharacterTypeTest extends \Eloquent\Typhoon\Test\TypeTestCase
{
  /**
   * @return array
   */
  public function typeValues()
  {
    $utf8Attributes = array(
      CharacterType::ATTRIBUTE_ENCODING => 'UTF-8',
    );

    $utf8OrUtf32Attributes = array(
      CharacterType::ATTRIBUTE_ENCODING => array(
        'UTF-8',
        'UTF-32',
      ),
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

      array(true, 'a'), // #9: character
      array(true, '0'), // #10: character
      array(true, '#'), // #11: character

      array(true,  chr(127), $utf8Attributes),   // #12: character with specific encoding success
      array(false, chr(128), $utf8Attributes),   // #13: character with specific encoding failure

      array(true,  mb_convert_encoding(chr(127), 'UTF-32', 'UTF-8'), $utf8OrUtf32Attributes),   // #14: character with specific encoding success
      array(false, chr(128),                                         $utf8OrUtf32Attributes),   // #15: character with specific encoding failure
      array(false, chr(127).chr(127),                                $utf8OrUtf32Attributes),   // #16: character with specific encoding length failure
    );
  }

  /**
   * @return string
   */
  protected function typeClass()
  {
    return __NAMESPACE__.'\CharacterType';
  }

  /**
   * @return string
   */
  protected function typeName()
  {
    return IntrinsicTypeName::NAME_CHARACTER()->value();
  }

  /**
   * @covers Eloquent\Typhoon\Type\CharacterType::configureAttributeSignature
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
    $expected->set(CharacterType::ATTRIBUTE_ENCODING, $stringOrArrayOfStringType);

    $type = new CharacterType;

    $this->assertEquals($expected, $type->typhoonAttributes()->signature());
  }

  // methods below must be manually overridden to implement @covers

  /**
   * @covers Eloquent\Typhoon\Type\CharacterType::__construct
   * @covers Eloquent\Typhoon\Type\CharacterType::typhoonCheck
   * @dataProvider typeValues
   * @group types
   * @group type
   * @group dynamic-type
   */
  public function testTyphoonCheck($expected, $value, $attributes = null) { parent::testTyphoonCheck($expected, $value, $attributes); }

  /**
   * @covers Eloquent\Typhoon\Type\CharacterType::typhoonName
   * @group types
   */
  public function testTyphoonName() { parent::testTyphoonName(); }
}
