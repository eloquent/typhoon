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
use Ezzatron\Typhoon\Primitive\String;
use stdClass;
use Phake;
use ReflectionObject;

class StreamTypeTest extends \Ezzatron\Typhoon\Test\TypeTestCase
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
      array(false, $this->socketFixture()),    // #9: socket
      array(true,  $this->streamFixture()),    // #10: stream
      array(true,  $this->fileFixture()),      // #11: file
      array(true,  $this->directoryFixture()), // #12: directory
    );
  }

  /**
   * @return array
   */
  public function typeValuesWithMetaData()
  {
    $data = array();
    
    $metaDataDefault = array(
      StreamType::META_DATA_MODE => 'mode',
      StreamType::META_DATA_TYPE => 'type',
      StreamType::META_DATA_WRAPPER => 'wrapper',
    );
    $metaDataRead = array(
      StreamType::META_DATA_MODE => 'r',
      StreamType::META_DATA_TYPE => 'type',
      StreamType::META_DATA_WRAPPER => 'wrapper',
    );
    $metaDataWrite = array(
      StreamType::META_DATA_MODE => 'w',
      StreamType::META_DATA_TYPE => 'type',
      StreamType::META_DATA_WRAPPER => 'wrapper',
    );
    $metaDataReadWrite = array(
      StreamType::META_DATA_MODE => 'r+',
      StreamType::META_DATA_TYPE => 'type',
      StreamType::META_DATA_WRAPPER => 'wrapper',
    );

    // #0: Local success
    $expected = true;
    $value = $this->streamFixture();
    $attributes = array(
      StreamType::ATTRIBUTE_LOCAL => true,
    );
    $isLocal = true;
    $metaData = $metaDataDefault;
    $data[] = array($expected, $value, $attributes, $isLocal, $metaData);

    // #1: Local failure
    $expected = false;
    $value = $this->streamFixture();
    $attributes = array(
      StreamType::ATTRIBUTE_LOCAL => true,
    );
    $isLocal = false;
    $metaData = $metaDataDefault;
    $data[] = array($expected, $value, $attributes, $isLocal, $metaData);

    // #2: Remote success
    $expected = true;
    $value = $this->streamFixture();
    $attributes = array(
      StreamType::ATTRIBUTE_LOCAL => false,
    );
    $isLocal = false;
    $metaData = $metaDataDefault;
    $data[] = array($expected, $value, $attributes, $isLocal, $metaData);

    // #3: Remote failure
    $expected = false;
    $value = $this->streamFixture();
    $attributes = array(
      StreamType::ATTRIBUTE_LOCAL => false,
    );
    $isLocal = true;
    $metaData = $metaDataDefault;
    $data[] = array($expected, $value, $attributes, $isLocal, $metaData);

    // #4: Meta data success
    $expected = true;
    $value = $this->streamFixture();
    $attributes = array(
      StreamType::ATTRIBUTE_MODE => 'mode',
      StreamType::ATTRIBUTE_TYPE => 'type',
      StreamType::ATTRIBUTE_WRAPPER => 'wrapper',
    );
    $isLocal = true;
    $metaData = $metaDataDefault;
    $data[] = array($expected, $value, $attributes, $isLocal, $metaData);

    // #5: Meta data mode failure
    $expected = false;
    $value = $this->streamFixture();
    $attributes = array(
      StreamType::ATTRIBUTE_MODE => 'foo',
      StreamType::ATTRIBUTE_TYPE => 'type',
      StreamType::ATTRIBUTE_WRAPPER => 'wrapper',
    );
    $isLocal = true;
    $metaData = $metaDataDefault;
    $data[] = array($expected, $value, $attributes, $isLocal, $metaData);

    // #6: Meta data type failure
    $expected = false;
    $value = $this->streamFixture();
    $attributes = array(
      StreamType::ATTRIBUTE_MODE => 'mode',
      StreamType::ATTRIBUTE_TYPE => 'foo',
      StreamType::ATTRIBUTE_WRAPPER => 'wrapper',
    );
    $isLocal = true;
    $metaData = $metaDataDefault;
    $data[] = array($expected, $value, $attributes, $isLocal, $metaData);

    // #7: Meta data wrapper failure
    $expected = false;
    $value = $this->streamFixture();
    $attributes = array(
      StreamType::ATTRIBUTE_MODE => 'mode',
      StreamType::ATTRIBUTE_TYPE => 'type',
      StreamType::ATTRIBUTE_WRAPPER => 'foo',
    );
    $isLocal = true;
    $metaData = $metaDataDefault;
    $data[] = array($expected, $value, $attributes, $isLocal, $metaData);

    // #8: Multiple attributes success
    $expected = true;
    $value = $this->streamFixture();
    $attributes = array(
      StreamType::ATTRIBUTE_LOCAL => true,
      StreamType::ATTRIBUTE_MODE => 'mode',
      StreamType::ATTRIBUTE_TYPE => 'type',
      StreamType::ATTRIBUTE_WRAPPER => 'wrapper',
    );
    $isLocal = true;
    $metaData = $metaDataDefault;
    $data[] = array($expected, $value, $attributes, $isLocal, $metaData);

    // #9: Meta data match but local failure
    $expected = false;
    $value = $this->streamFixture();
    $attributes = array(
      StreamType::ATTRIBUTE_LOCAL => true,
      StreamType::ATTRIBUTE_MODE => 'mode',
      StreamType::ATTRIBUTE_TYPE => 'type',
      StreamType::ATTRIBUTE_WRAPPER => 'wrapper',
    );
    $isLocal = false;
    $metaData = $metaDataDefault;
    $data[] = array($expected, $value, $attributes, $isLocal, $metaData);

    // #10: Multiple values for mode success
    $expected = true;
    $value = $this->streamFixture();
    $attributes = array(
      StreamType::ATTRIBUTE_MODE => array(
        'f',
        'm',
      )
    );
    $isLocal = true;
    $metaData = $metaDataDefault;
    $data[] = array($expected, $value, $attributes, $isLocal, $metaData);

    // #11: Multiple values for mode failure
    $expected = false;
    $value = $this->streamFixture();
    $attributes = array(
      StreamType::ATTRIBUTE_MODE => array(
        'f',
        'mobe',
      )
    );
    $isLocal = true;
    $metaData = $metaDataDefault;
    $data[] = array($expected, $value, $attributes, $isLocal, $metaData);

    // #12: Multiple values for type success
    $expected = true;
    $value = $this->streamFixture();
    $attributes = array(
      StreamType::ATTRIBUTE_TYPE => array(
        'foo',
        'type',
      )
    );
    $isLocal = true;
    $metaData = $metaDataDefault;
    $data[] = array($expected, $value, $attributes, $isLocal, $metaData);

    // #13: Multiple values for type failure
    $expected = false;
    $value = $this->streamFixture();
    $attributes = array(
      StreamType::ATTRIBUTE_TYPE => array(
        'foo',
        'bar',
      )
    );
    $isLocal = true;
    $metaData = $metaDataDefault;
    $data[] = array($expected, $value, $attributes, $isLocal, $metaData);

    // #14: Multiple values for wrapper success
    $expected = true;
    $value = $this->streamFixture();
    $attributes = array(
      StreamType::ATTRIBUTE_WRAPPER => array(
        'foo',
        'wrapper',
      )
    );
    $isLocal = true;
    $metaData = $metaDataDefault;
    $data[] = array($expected, $value, $attributes, $isLocal, $metaData);

    // #15: Multiple values for wrapper failure
    $expected = false;
    $value = $this->streamFixture();
    $attributes = array(
      StreamType::ATTRIBUTE_WRAPPER => array(
        'foo',
        'bar',
      )
    );
    $isLocal = true;
    $metaData = $metaDataDefault;
    $data[] = array($expected, $value, $attributes, $isLocal, $metaData);

    // #16: Read success with read mode
    $expected = true;
    $value = $this->streamFixture();
    $attributes = array(
      StreamType::ATTRIBUTE_READ => true,
    );
    $isLocal = true;
    $metaData = $metaDataRead;
    $data[] = array($expected, $value, $attributes, $isLocal, $metaData);

    // #17: Read success with read/write mode
    $expected = true;
    $value = $this->streamFixture();
    $attributes = array(
      StreamType::ATTRIBUTE_READ => true,
    );
    $isLocal = true;
    $metaData = $metaDataReadWrite;
    $data[] = array($expected, $value, $attributes, $isLocal, $metaData);

    // #18: Read failure with write mode
    $expected = false;
    $value = $this->streamFixture();
    $attributes = array(
      StreamType::ATTRIBUTE_READ => true,
    );
    $isLocal = true;
    $metaData = $metaDataWrite;
    $data[] = array($expected, $value, $attributes, $isLocal, $metaData);

    // #19: Read failure with read mode
    $expected = false;
    $value = $this->streamFixture();
    $attributes = array(
      StreamType::ATTRIBUTE_READ => false,
    );
    $isLocal = true;
    $metaData = $metaDataRead;
    $data[] = array($expected, $value, $attributes, $isLocal, $metaData);

    // #20: Read failure with read/write mode
    $expected = false;
    $value = $this->streamFixture();
    $attributes = array(
      StreamType::ATTRIBUTE_READ => false,
    );
    $isLocal = true;
    $metaData = $metaDataReadWrite;
    $data[] = array($expected, $value, $attributes, $isLocal, $metaData);

    // #21: Read success with write mode
    $expected = true;
    $value = $this->streamFixture();
    $attributes = array(
      StreamType::ATTRIBUTE_READ => false,
    );
    $isLocal = true;
    $metaData = $metaDataWrite;
    $data[] = array($expected, $value, $attributes, $isLocal, $metaData);

    // #22: Write success with write mode
    $expected = true;
    $value = $this->streamFixture();
    $attributes = array(
      StreamType::ATTRIBUTE_WRITE => true,
    );
    $isLocal = true;
    $metaData = $metaDataWrite;
    $data[] = array($expected, $value, $attributes, $isLocal, $metaData);

    // #23: Write success with read/write mode
    $expected = true;
    $value = $this->streamFixture();
    $attributes = array(
      StreamType::ATTRIBUTE_WRITE => true,
    );
    $isLocal = true;
    $metaData = $metaDataReadWrite;
    $data[] = array($expected, $value, $attributes, $isLocal, $metaData);

    // #24: Write failure with read mode
    $expected = false;
    $value = $this->streamFixture();
    $attributes = array(
      StreamType::ATTRIBUTE_WRITE => true,
    );
    $isLocal = true;
    $metaData = $metaDataRead;
    $data[] = array($expected, $value, $attributes, $isLocal, $metaData);

    // #25: Write failure with write mode
    $expected = false;
    $value = $this->streamFixture();
    $attributes = array(
      StreamType::ATTRIBUTE_WRITE => false,
    );
    $isLocal = true;
    $metaData = $metaDataWrite;
    $data[] = array($expected, $value, $attributes, $isLocal, $metaData);

    // #26: Write failure with read/write mode
    $expected = false;
    $value = $this->streamFixture();
    $attributes = array(
      StreamType::ATTRIBUTE_WRITE => false,
    );
    $isLocal = true;
    $metaData = $metaDataReadWrite;
    $data[] = array($expected, $value, $attributes, $isLocal, $metaData);

    // #27: Write success with write mode
    $expected = true;
    $value = $this->streamFixture();
    $attributes = array(
      StreamType::ATTRIBUTE_WRITE => false,
    );
    $isLocal = true;
    $metaData = $metaDataRead;
    $data[] = array($expected, $value, $attributes, $isLocal, $metaData);

    // #28: Read/write success with read/write mode
    $expected = true;
    $value = $this->streamFixture();
    $attributes = array(
      StreamType::ATTRIBUTE_READ => true,
      StreamType::ATTRIBUTE_WRITE => true,
    );
    $isLocal = true;
    $metaData = $metaDataReadWrite;
    $data[] = array($expected, $value, $attributes, $isLocal, $metaData);

    // #29: Read/write failure with read mode
    $expected = false;
    $value = $this->streamFixture();
    $attributes = array(
      StreamType::ATTRIBUTE_READ => true,
      StreamType::ATTRIBUTE_WRITE => true,
    );
    $isLocal = true;
    $metaData = $metaDataRead;
    $data[] = array($expected, $value, $attributes, $isLocal, $metaData);

    // #30: Read/write failure with write mode
    $expected = false;
    $value = $this->streamFixture();
    $attributes = array(
      StreamType::ATTRIBUTE_READ => true,
      StreamType::ATTRIBUTE_WRITE => true,
    );
    $isLocal = true;
    $metaData = $metaDataWrite;
    $data[] = array($expected, $value, $attributes, $isLocal, $metaData);

    // #31: Writable stream in binary mode success
    $expected = true;
    $value = $this->streamFixture();
    $attributes = array(
      StreamType::ATTRIBUTE_WRITE => true,
      StreamType::ATTRIBUTE_MODE => 'b',
    );
    $isLocal = true;
    $metaData = array(
      StreamType::META_DATA_MODE => 'wb',
      StreamType::META_DATA_TYPE => 'type',
      StreamType::META_DATA_WRAPPER => 'wrapper',
    );
    $data[] = array($expected, $value, $attributes, $isLocal, $metaData);

    // #32: Writable stream in binary mode failure
    $expected = false;
    $value = $this->streamFixture();
    $attributes = array(
      StreamType::ATTRIBUTE_WRITE => true,
      StreamType::ATTRIBUTE_MODE => 'b',
    );
    $isLocal = true;
    $metaData = array(
      StreamType::META_DATA_MODE => 'wt',
      StreamType::META_DATA_TYPE => 'type',
      StreamType::META_DATA_WRAPPER => 'wrapper',
    );
    $data[] = array($expected, $value, $attributes, $isLocal, $metaData);

    // #33: Writable stream in binary mode failure
    $expected = false;
    $value = $this->streamFixture();
    $attributes = array(
      StreamType::ATTRIBUTE_WRITE => true,
      StreamType::ATTRIBUTE_MODE => 'b',
    );
    $isLocal = true;
    $metaData = array(
      StreamType::META_DATA_MODE => 'w',
      StreamType::META_DATA_TYPE => 'type',
      StreamType::META_DATA_WRAPPER => 'wrapper',
    );
    $data[] = array($expected, $value, $attributes, $isLocal, $metaData);

    return $data;
  }

  /**
   * @return string
   */
  protected function typeClass()
  {
    return __NAMESPACE__.'\StreamType';
  }

  /**
   * @covers Ezzatron\Typhoon\Type\StreamType::isLocal
   * @group types
   * @group type
   * @group dynamic-type
   */
  public function testIsLocal()
  {
    $type = $this->typeFixture();

    $reflector = new ReflectionObject($type);
    $method = $reflector->getMethod('isLocal');
    $method->setAccessible(true);

    $this->assertInternalType('boolean', $method->invokeArgs($type, array($this->streamFixture())));
  }

  /**
   * @covers Ezzatron\Typhoon\Type\StreamType::getMetaData
   * @group types
   * @group type
   * @group dynamic-type
   */
  public function testGetMetaData()
  {
    $type = $this->typeFixture();

    $reflector = new ReflectionObject($type);
    $method = $reflector->getMethod('getMetaData');
    $method->setAccessible(true);

    $expectedKeys = array(
      StreamType::META_DATA_MODE,
      StreamType::META_DATA_TYPE,
      StreamType::META_DATA_WRAPPER,
    );

    $actual = $method->invokeArgs($type, array($this->streamFixture()));

    foreach ($expectedKeys as $expected)
    {
      $this->assertTrue(array_key_exists($expected, $actual));
    }
  }

  /**
   * @return array
   */
  public function modeMatchesData()
  {
    return array(
      array(true,  'r', 'r'),
      array(true,  'rb', 'rb'),
      array(true,  'rb', 'br'),
      array(true,  'rb', 'r+b'),
      array(true,  'rb', 'rb+'),
      array(true,  'r+b', 'r+b'),
      array(true,  'r+b', 'rb+'),

      array(false, 'w', 'r'),
      array(false, 'rb', 'r'),
      array(false, 'rb', 'wb'),
      array(false, 'rb', 'w+b'),
      array(false, 'r+b', 'rb'),
    );
  }

  /**
   * @covers Ezzatron\Typhoon\Type\StreamType::modeMatches
   * @dataProvider modeMatchesData
   * @group types
   * @group type
   * @group dynamic-type
   */
  public function testModeMatches($expected, $pattern, $mode)
  {
    $type = $this->typeFixture();

    $reflector = new ReflectionObject($type);
    $method = $reflector->getMethod('modeMatches');
    $method->setAccessible(true);

    $this->assertSame($expected, $method->invoke($type, $pattern, $mode));
  }
  
  /**
   * @return array
   */
  public function modeIsReadData()
  {
    return array(
      array(true,  'r'),
      array(true,  'r+'),
      array(true,  'w+'),
      array(true,  'a+'),
      array(true,  'x+'),
      array(true,  'c+'),
      array(true,  'rb'),
      array(true,  'r+b'),
      array(true,  'w+b'),
      array(true,  'a+b'),
      array(true,  'x+b'),
      array(true,  'c+b'),
      array(true,  'rt'),
      array(true,  'r+t'),
      array(true,  'w+t'),
      array(true,  'a+t'),
      array(true,  'x+t'),
      array(true,  'c+t'),

      array(false, 'w'),
      array(false, 'a'),
      array(false, 'x'),
      array(false, 'c'),
      array(false, 'wb'),
      array(false, 'ab'),
      array(false, 'xb'),
      array(false, 'cb'),
      array(false, 'wt'),
      array(false, 'at'),
      array(false, 'xt'),
      array(false, 'ct'),
    );
  }

  /**
   * @covers Ezzatron\Typhoon\Type\StreamType::modeIsRead
   * @dataProvider modeIsReadData
   * @group types
   * @group type
   * @group dynamic-type
   */
  public function testModeIsRead($expected, $mode)
  {
    $type = $this->typeFixture();

    $reflector = new ReflectionObject($type);
    $method = $reflector->getMethod('modeIsRead');
    $method->setAccessible(true);

    $this->assertSame($expected, $method->invoke($type, $mode));
  }

  /**
   * @return array
   */
  public function modeIsWriteData()
  {
    return array(
      array(true,  'w'),
      array(true,  'a'),
      array(true,  'x'),
      array(true,  'c'),
      array(true,  'r+'),
      array(true,  'w+'),
      array(true,  'a+'),
      array(true,  'x+'),
      array(true,  'c+'),
      array(true,  'wb'),
      array(true,  'ab'),
      array(true,  'xb'),
      array(true,  'cb'),
      array(true,  'r+b'),
      array(true,  'w+b'),
      array(true,  'a+b'),
      array(true,  'x+b'),
      array(true,  'c+b'),
      array(true,  'wt'),
      array(true,  'at'),
      array(true,  'xt'),
      array(true,  'ct'),
      array(true,  'r+t'),
      array(true,  'w+t'),
      array(true,  'a+t'),
      array(true,  'x+t'),
      array(true,  'c+t'),

      array(false, 'r'),
      array(false, 'rb'),
      array(false, 'rt'),
    );
  }

  /**
   * @covers Ezzatron\Typhoon\Type\StreamType::modeIsWrite
   * @dataProvider modeIsWriteData
   * @group types
   * @group type
   * @group dynamic-type
   */
  public function testModeIsWrite($expected, $mode)
  {
    $type = $this->typeFixture();

    $reflector = new ReflectionObject($type);
    $method = $reflector->getMethod('modeIsWrite');
    $method->setAccessible(true);

    $this->assertSame($expected, $method->invoke($type, $mode));
  }

  /**
   * @covers Ezzatron\Typhoon\Type\StreamType::configureAttributeSignature
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
    $expected->set(StreamType::ATTRIBUTE_LOCAL, new BooleanType);
    $expected->set(StreamType::ATTRIBUTE_MODE, $stringOrArrayOfStringType);
    $expected->set(StreamType::ATTRIBUTE_READ, new BooleanType);
    $expected->set(StreamType::ATTRIBUTE_TYPE, $stringOrArrayOfStringType);
    $expected->set(StreamType::ATTRIBUTE_WRAPPER, $stringOrArrayOfStringType);
    $expected->set(StreamType::ATTRIBUTE_WRITE, new BooleanType);

    $type = new StreamType;

    $this->assertEquals($expected, $type->typhoonAttributes()->signature());
  }

  /**
   * @covers Ezzatron\Typhoon\Type\StreamType::__construct
   * @covers Ezzatron\Typhoon\Type\StreamType::typhoonCheck
   * @dataProvider typeValuesWithMetaData
   * @group types
   * @group type
   * @group dynamic-type
   */
  public function testTyphoonCheckWithMetaData($expected, $value, array $attributes, $isLocal, array $metaData)
  {
    $type = Phake::partialMock($this->typeClass(), $attributes);
    Phake::when($type)->isLocal($value)->thenReturn($isLocal);
    Phake::when($type)->getMetaData($value)->thenReturn($metaData);

    $this->assertSame($expected, $type->typhoonCheck($value));
  }

  // methods below must be manually overridden to implement @covers

  /**
   * @covers Ezzatron\Typhoon\Type\StreamType::__construct
   * @covers Ezzatron\Typhoon\Type\StreamType::typhoonCheck
   * @dataProvider typeValues
   * @group types
   * @group type
   * @group dynamic-type
   */
  public function testTyphoonCheck($expected, $value, $attributes = null) { parent::testTyphoonCheck($expected, $value, $attributes); }
}
