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

class ResourceTypeTest extends \Eloquent\Typhoon\Test\TypeTestCase
{
  /**
   * @return array
   */
  public function typeValues()
  {
    $streamAttributes = array(
      ResourceType::ATTRIBUTE_TYPE => ResourceType::TYPE_STREAM,
    );

    $streamOrSocketAttributes = array(
      ResourceType::ATTRIBUTE_TYPE => array(
        ResourceType::TYPE_STREAM,
        ResourceType::TYPE_SOCKET,
      ),
    );

    $typeValues = array(
      array(false, null),                      // #0: null
      array(false, true),                      // #1: boolean
      array(false, 'string'),                  // #2: string
      array(false, 1),                         // #3: integer
      array(false, .1),                        // #4: float
      array(false, array()),                   // #5: array
      array(false, new stdClass),              // #6: object
      array(false, function(){}),              // #7: closure
      array(true,  $this->resourceFixture()),  // #8: resource
      array(true,  $this->streamFixture()),    // #9: stream
      array(true,  $this->fileFixture()),      // #10: file
      array(true,  $this->directoryFixture()), // #11: directory

      array(false, $this->resourceFixture(),   $streamAttributes),  // #12: resource of type stream failure
      array(true,  $this->streamFixture(),     $streamAttributes),  // #13: resource of type stream success
      array(true,  $this->fileFixture(),       $streamAttributes),  // #14: resource of type stream success (file)
      array(true,  $this->directoryFixture(),  $streamAttributes),  // #15: resource of type stream success (directory)

      array(false, $this->resourceFixture(),   $streamOrSocketAttributes),  // #16: resource of type stream or socket failure
      array(true,  $this->streamFixture(),     $streamOrSocketAttributes),  // #17: resource of type stream or socket success
    );

    if (extension_loaded('sockets'))
    {
      $typeValues[] = array(true, $this->socketFixture());                            // #18: resource of type stream or socket success
      $typeValues[] = array(true, $this->socketFixture(), $streamOrSocketAttributes); // #19: resource of type stream or socket success
    }

    return $typeValues;
  }

  /**
   * @return string
   */
  protected function typeClass()
  {
    return __NAMESPACE__.'\ResourceType';
  }

  /**
   * @return string
   */
  protected function typeName()
  {
    return IntrinsicTypeName::NAME_RESOURCE()->_value();
  }

  /**
   * @covers Eloquent\Typhoon\Type\ResourceType::configureAttributeSignature
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
    $expected->set(ResourceType::ATTRIBUTE_TYPE, $stringOrArrayOfStringType);

    $type = new ResourceType;

    $this->assertEquals($expected, $type->typhoonAttributes()->signature());
  }

  // methods below must be manually overridden to implement @covers

  /**
   * @covers Eloquent\Typhoon\Type\ResourceType::typhoonCheck
   * @dataProvider typeValues
   * @group types
   * @group type
   * @group dynamic-type
   */
  public function testTyphoonCheck($expected, $value, $attributes = null) { parent::testTyphoonCheck($expected, $value, $attributes); }

  /**
   * @covers Eloquent\Typhoon\Type\ResourceType::typhoonName
   * @group types
   */
  public function testTyphoonName() { parent::testTyphoonName(); }
}
