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
use ReflectionObject;

class StreamTypeTest extends \Ezzatron\Typhoon\Test\TypeTestCase
{
  /**
   * @return array
   */
  public function typeValues()
  {
    $stdioAttributes = array(
      StreamType::ATTRIBUTE_TYPE => StreamType::TYPE_STDIO,
    );

    $stdioOrDirAttributes = array(
      StreamType::ATTRIBUTE_TYPE => array(
        StreamType::TYPE_STDIO,
        StreamType::TYPE_DIR,
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
      array(false, $this->socketFixture()),    // #9: socket
      array(true,  $this->streamFixture()),    // #10: stream
      array(true,  $this->fileFixture()),      // #11: file
      array(true,  $this->directoryFixture()), // #12: directory

      array(false, $this->streamFixture(),    $stdioAttributes), // #13: stream of type STDIO failure
      array(true,  $this->fileFixture(),      $stdioAttributes), // #14: stream of type STDIO success
      array(false, $this->directoryFixture(), $stdioAttributes), // #15: stream of type STDIO failure

      array(false, $this->streamFixture(),    $stdioOrDirAttributes), // #13: stream of type STDIO or dir failure
      array(true,  $this->fileFixture(),      $stdioOrDirAttributes), // #14: stream of type STDIO or dir success
      array(true,  $this->directoryFixture(), $stdioOrDirAttributes), // #15: stream of type STDIO or dir success
    );
  }

  /**
   * @return string
   */
  protected function typeClass()
  {
    return __NAMESPACE__.'\StreamType';
  }

  /**
   * @covers Ezzatron\Typhoon\Type\StreamType::typhoonCheck
   * @group types
   * @group type
   * @group dynamic-type
   */
  public function testTyphoonCheckWithAttributes()
  {
    $stream = $this->streamFixture();

    $type = $this->getMock($this->typeClass(), array('isLocal'), array(array(
      StreamType::ATTRIBUTE_LOCAL => true,
    )));
    $type
      ->expects($this->once())
      ->method('isLocal')
      ->with($stream)
      ->will($this->returnValue(true))
    ;
    
    $this->assertTrue($type->typhoonCheck($stream));

    $type = $this->getMock($this->typeClass(), array('isLocal'), array(array(
      StreamType::ATTRIBUTE_LOCAL => false,
    )));
    $type
      ->expects($this->once())
      ->method('isLocal')
      ->with($stream)
      ->will($this->returnValue(false))
    ;
    
    $this->assertTrue($type->typhoonCheck($stream));

    $type = $this->getMock($this->typeClass(), array('isLocal'), array(array(
      StreamType::ATTRIBUTE_LOCAL => true,
    )));
    $type
      ->expects($this->once())
      ->method('isLocal')
      ->with($stream)
      ->will($this->returnValue(false))
    ;
    
    $this->assertFalse($type->typhoonCheck($stream));

    $type = $this->getMock($this->typeClass(), array('isLocal'), array(array(
      StreamType::ATTRIBUTE_LOCAL => false,
    )));
    $type
      ->expects($this->once())
      ->method('isLocal')
      ->with($stream)
      ->will($this->returnValue(true))
    ;
    
    $this->assertFalse($type->typhoonCheck($stream));

    $type = $this->getMock($this->typeClass(), array('getMetaData'), array(array(
      StreamType::ATTRIBUTE_MODE => 'mode',
      StreamType::ATTRIBUTE_TYPE => 'type',
      StreamType::ATTRIBUTE_WRAPPER => 'wrapper',
    )));
    $type
      ->expects($this->once())
      ->method('getMetaData')
      ->with($stream)
      ->will($this->returnValue(array(
        StreamType::META_DATA_MODE => 'mode',
        StreamType::META_DATA_TYPE => 'type',
        StreamType::META_DATA_WRAPPER => 'wrapper',
      )))
    ;

    $this->assertTrue($type->typhoonCheck($stream));

    $type = $this->getMock($this->typeClass(), array('getMetaData'), array(array(
      StreamType::ATTRIBUTE_MODE => 'foo',
      StreamType::ATTRIBUTE_TYPE => 'type',
      StreamType::ATTRIBUTE_WRAPPER => 'wrapper',
    )));
    $type
      ->expects($this->once())
      ->method('getMetaData')
      ->with($stream)
      ->will($this->returnValue(array(
        StreamType::META_DATA_MODE => 'mode',
        StreamType::META_DATA_TYPE => 'type',
        StreamType::META_DATA_WRAPPER => 'wrapper',
      )))
    ;

    $this->assertFalse($type->typhoonCheck($stream));

    $type = $this->getMock($this->typeClass(), array('getMetaData'), array(array(
      StreamType::ATTRIBUTE_MODE => 'mode',
      StreamType::ATTRIBUTE_TYPE => 'foo',
      StreamType::ATTRIBUTE_WRAPPER => 'wrapper',
    )));
    $type
      ->expects($this->once())
      ->method('getMetaData')
      ->with($stream)
      ->will($this->returnValue(array(
        StreamType::META_DATA_MODE => 'mode',
        StreamType::META_DATA_TYPE => 'type',
        StreamType::META_DATA_WRAPPER => 'wrapper',
      )))
    ;

    $this->assertFalse($type->typhoonCheck($stream));

    $type = $this->getMock($this->typeClass(), array('getMetaData'), array(array(
      StreamType::ATTRIBUTE_MODE => 'mode',
      StreamType::ATTRIBUTE_TYPE => 'type',
      StreamType::ATTRIBUTE_WRAPPER => 'foo',
    )));
    $type
      ->expects($this->once())
      ->method('getMetaData')
      ->with($stream)
      ->will($this->returnValue(array(
        StreamType::META_DATA_MODE => 'mode',
        StreamType::META_DATA_TYPE => 'type',
        StreamType::META_DATA_WRAPPER => 'wrapper',
      )))
    ;

    $this->assertFalse($type->typhoonCheck($stream));
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
   * @covers Ezzatron\Typhoon\Type\StreamType::configureAttributeSignature
   * @group types
   * @group type
   * @group dynamic-type
   */
  public function testConfigureAttributeSignature()
  {
    $typeArrayType = new ArrayType;
    $typeArrayType->setTyphoonSubType(new StringType);
    $typeType = new Composite\OrType;
    $typeType->addTyphoonType(new StringType);
    $typeType->addTyphoonType($typeArrayType);

    $expected = new AttributeSignature;
    $expected->setHolderName(new String($this->typeClass()));
    $expected[StreamType::ATTRIBUTE_LOCAL] = new BooleanType;
    $expected[StreamType::ATTRIBUTE_MODE] = new StringType;
    $expected[StreamType::ATTRIBUTE_TYPE] = $typeType;
    $expected[StreamType::ATTRIBUTE_WRAPPER] = new StringType;

    $type = new StreamType;
    $actual = $type->typhoonAttributes()->signature();

    $this->assertEquals($expected, $actual);

    $type = new StreamType;

    $this->assertEquals($actual, $type->typhoonAttributes()->signature());
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