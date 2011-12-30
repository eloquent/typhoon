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

class ResourceTypeTest extends \Ezzatron\Typhoon\Test\TypeTestCase
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

    return array(
      array(false, null),                      // #0: null
      array(false, true),                      // #1: boolean
      array(false, 'string'),                  // #2: string
      array(false, 1),                         // #3: integer
      array(false, .1),                        // #4: float
      array(false, array()),                   // #5: array
      array(false, new stdClass),              // #6: object
      array(false, function(){}),              // #7: closure
      array(true,  $this->resourceFixture()),  // #8: resource
      array(true,  $this->socketFixture()),    // #9: socket
      array(true,  $this->streamFixture()),    // #10: stream
      array(true,  $this->fileFixture()),      // #11: file
      array(true,  $this->directoryFixture()), // #12: directory

      array(false, $this->resourceFixture(),   $streamAttributes),  // #13: resource of type stream failure
      array(true,  $this->streamFixture(),     $streamAttributes),  // #14: resource of type stream success
      array(true,  $this->fileFixture(),       $streamAttributes),  // #15: resource of type stream success (file)
      array(true,  $this->directoryFixture(),  $streamAttributes),  // #16: resource of type stream success (directory)

      array(false, $this->resourceFixture(),   $streamOrSocketAttributes),  // #17: resource of type stream or socket failure
      array(true,  $this->streamFixture(),     $streamOrSocketAttributes),  // #18: resource of type stream or socket success
      array(true,  $this->socketFixture(),     $streamOrSocketAttributes),  // #19: resource of type stream or socket success
    );
  }

  /**
   * @return string
   */
  protected function typeClass()
  {
    return __NAMESPACE__.'\ResourceType';
  }

  /**
   * @covers Ezzatron\Typhoon\Type\ResourceType::configureAttributeSignature
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
   * @covers Ezzatron\Typhoon\Type\ResourceType::typhoonCheck
   * @dataProvider typeValues
   * @group types
   * @group type
   * @group dynamic-type
   */
  public function testTyphoonCheck($expected, $value, $attributes = null) { parent::testTyphoonCheck($expected, $value, $attributes); }
}