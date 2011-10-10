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

use Ezzatron\Typhoon\Attribute\Attributes;
use Ezzatron\Typhoon\Attribute\AttributeSignature;
use Ezzatron\Typhoon\Primitive\String;
use stdClass;

class NodeTypeTest extends \Ezzatron\Typhoon\Test\TypeTestCase
{
  /**
   * @return array
   */
  public function typeValues()
  {
    $fileAttributes = new Attributes(array(
      NodeType::ATTRIBUTE_TYPE => NodeType::TYPE_FILE,
    ));
    $directoryAttributes = new Attributes(array(
      NodeType::ATTRIBUTE_TYPE => NodeType::TYPE_DIRECTORY,
    ));
    
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
      array(false, $this->streamFixture()),    // #9: stream
      array(true,  $this->fileFixture()),      // #10: file
      array(true,  $this->directoryFixture()), // #11: directory
        
      array(true,  $this->fileFixture(), $fileAttributes),            // #12: node of type file success
      array(false, $this->directoryFixture(), $fileAttributes),       // #13: node of type file failure
      array(false, $this->fileFixture(), $directoryAttributes),       // #14: node of type directory failure
      array(true,  $this->directoryFixture(), $directoryAttributes),  // #15: node of type directory success
    );
  }

  /**
   * @return string
   */
  protected function typeClass()
  {
    return __NAMESPACE__.'\NodeType';
  }

  /**
   * @covers Ezzatron\Typhoon\Type\NodeType::configureAttributeSignature
   */
  public function testConfigureAttributeSignature()
  {
    $expected = new AttributeSignature;
    $expected->setHolderName(new String($this->typeClass()));
    $expected[NodeType::ATTRIBUTE_TYPE] = new StringType;

    $type = new NodeType;
    $actual = $type->typhoonAttributes()->signature();

    $this->assertEquals($expected, $actual);

    $type = new NodeType;

    $this->assertEquals($actual, $type->typhoonAttributes()->signature());
  }

  // methods below must be manually overridden to implement @covers

  /**
   * @covers Ezzatron\Typhoon\Type\NodeType::__construct
   * @covers Ezzatron\Typhoon\Type\NodeType::typhoonCheck
   * @dataProvider typeValues
   * @group typhoon_types
   */
  public function testTyphoonCheck($expected, $value, Attributes $attributes = null) { parent::testTyphoonCheck($expected, $value, $attributes); }
}