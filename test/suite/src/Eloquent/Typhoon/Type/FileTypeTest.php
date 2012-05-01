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

class FileTypeTest extends \Eloquent\Typhoon\Test\TypeTestCase
{
  /**
   * @return array
   */
  public function typeValues()
  {
    $localAttributes = array(
      DirectoryType::ATTRIBUTE_LOCAL => true,
    );
    $remoteAttributes = array(
      DirectoryType::ATTRIBUTE_LOCAL => false,
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
      array(false, $this->streamFixture()),    // #9: stream
      array(true,  $this->fileFixture()),      // #10: file
      array(false, $this->directoryFixture()), // #11: directory

      array(true,  $this->fileFixture(), $localAttributes),  // #12: local file success
      array(false, $this->fileFixture(), $remoteAttributes), // #13: remote file failure
    );
  }

  /**
   * @return string
   */
  protected function typeClass()
  {
    return __NAMESPACE__.'\FileType';
  }

  /**
   * @return string
   */
  protected function typeName()
  {
    return IntrinsicTypeName::NAME_FILE()->_value();
  }

  /**
   * @covers Eloquent\Typhoon\Type\FileType::configureAttributeSignature
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
    $expected->set(FileType::ATTRIBUTE_LOCAL, new BooleanType);
    $expected->set(FileType::ATTRIBUTE_MODE, $stringOrArrayOfStringType);
    $expected->set(FileType::ATTRIBUTE_READ, new BooleanType);
    $expected->set(FileType::ATTRIBUTE_WRAPPER, $stringOrArrayOfStringType);
    $expected->set(FileType::ATTRIBUTE_WRITE, new BooleanType);

    $type = new FileType;

    $this->assertEquals($expected, $type->typhoonAttributes()->signature());
  }

  // methods below must be manually overridden to implement @covers

  /**
   * @covers Eloquent\Typhoon\Type\FileType::__construct
   * @covers Eloquent\Typhoon\Type\FileType::typhoonCheck
   * @dataProvider typeValues
   * @group types
   * @group type
   */
  public function testTyphoonCheck($expected, $value, $attributes = null) { parent::testTyphoonCheck($expected, $value, $attributes); }

  /**
   * @covers Eloquent\Typhoon\Type\FileType::typhoonName
   * @group types
   */
  public function testTyphoonName() { parent::testTyphoonName(); }
}
