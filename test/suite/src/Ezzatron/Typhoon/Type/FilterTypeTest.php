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

use Ezzatron\Typhoon\Attribute\AttributeSignature;
use Ezzatron\Typhoon\Primitive\Boolean;
use Ezzatron\Typhoon\Primitive\String;

class FilterTypeTest extends \Ezzatron\Typhoon\Test\TypeTestCase
{
  /**
   * @return array
   */
  public function typeValues()
  {
    $attributesInteger = array(
      FilterType::ATTRIBUTE_FILTER => FILTER_VALIDATE_INT,
      FilterType::ATTRIBUTE_OPTIONS => array(
        'options' => array(
          'min_range' => 10,
        ),
      ),
    );
    $attributesEmail = array(
      FilterType::ATTRIBUTE_FILTER => FILTER_VALIDATE_EMAIL,
    );

    return array(
      array(true,  100,              $attributesInteger),  // #0: integer pass
      array(false, 'foo',            $attributesInteger),  // #1: integer fail
      array(false, 1,                $attributesInteger),  // #2: integer fail because of attributes

      array(true,  'foo@example.org',  $attributesEmail),  // #3: email pass
      array(false, 'bar',              $attributesEmail),  // #4: email fail
    );
  }

  /**
   * @return string
   */
  protected function typeClass()
  {
    return __NAMESPACE__.'\FilterType';
  }

  /**
   * @covers Ezzatron\Typhoon\Type\FilterType::configureAttributeSignature
   * @group types
   * @group type
   * @group dynamic-type
   */
  public function testConfigureAttributeSignature()
  {
    $optionsArrayType = new ArrayType;
    $optionsArrayType->setTyphoonKeyType(new StringType);
    $optionsType = new Composite\OrType;
    $optionsType->addTyphoonType($optionsArrayType);
    $optionsType->addTyphoonType(new IntegerType);

    $expected = new AttributeSignature;
    $expected->setHolderName(new String($this->typeClass()));

    $expected->set(FilterType::ATTRIBUTE_FILTER, new IntegerType, new Boolean(true));
    $expected->set(FilterType::ATTRIBUTE_OPTIONS, $optionsType);

    $type = new FilterType(array(
      FilterType::ATTRIBUTE_FILTER => FILTER_VALIDATE_BOOLEAN,
    ));

    $this->assertEquals($expected, $type->typhoonAttributes()->signature());
  }

  // methods below must be manually overridden to implement @covers

  /**
   * @covers Ezzatron\Typhoon\Type\FilterType::typhoonCheck
   * @dataProvider typeValues
   * @group types
   * @group type
   * @group dynamic-type
   */
  public function testTyphoonCheck($expected, $value, $attributes = null) { parent::testTyphoonCheck($expected, $value, $attributes); }
}
