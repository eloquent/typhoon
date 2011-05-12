<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Typhoon;

use Typhoon\Test\TestCase;

class StandardDynamicTypeTest extends TestCase
{
  protected function setUp()
  {
    parent::setUp();
    
    $this->_type = $this->getMockForAbstractClass(__NAMESPACE__.'\StandardDynamicType');
  }

  /**
   * @covers Typhoon\StandardDynamicType::setTyphoonAttribute
   * @covers Typhoon\StandardDynamicType::typhoonAttributes
   * @covers Typhoon\StandardDynamicType::hasAttribute
   * @covers Typhoon\StandardDynamicType::attribute
   * @covers Typhoon\StandardDynamicType::assertAttribute
   */
  public function testAttributes()
  {
    $this->_type
      ->expects($this->any())
      ->method('attributeSupported')
      ->will($this->returnValue(true))
    ;

    $this->assertEquals(array(), $this->_type->typhoonAttributes());
    $this->assertFalse($this->_type->hasAttribute('foo'));
    $this->assertEquals('bar', $this->_type->attribute('foo', 'bar'));

    $this->_type->setTyphoonAttribute('foo', 'bar');

    $this->assertEquals(array('foo' => 'bar'), $this->_type->typhoonAttributes());
    $this->assertTrue($this->_type->hasAttribute('foo'));
    $this->assertEquals('bar', $this->_type->attribute('foo'));
    $this->assertEquals('bar', $this->_type->attribute('foo', 'baz'));

    $this->_type->setTyphoonAttribute('baz', 'qux');

    $this->assertEquals(array('foo' => 'bar', 'baz' => 'qux'), $this->_type->typhoonAttributes());
    $this->assertEquals('qux', $this->_type->attribute('baz'));

    $this->_type->setTyphoonAttribute('doom', null);

    $this->assertEquals(array('foo' => 'bar', 'baz' => 'qux', 'doom' => null), $this->_type->typhoonAttributes());
    $this->assertTrue($this->_type->hasAttribute('doom'));
    $this->assertNull($this->_type->attribute('doom'));
    $this->assertEquals('foo', $this->_type->attribute('doom', 'foo'));
  }

  /**
   * @covers Typhoon\StandardDynamicType::setTyphoonAttribute
   * @covers Typhoon\StandardDynamicType::assertAttribute
   */
  public function testSetTyphoonAttributeFailureUnsupported()
  {
    $this->setExpectedException(__NAMESPACE__.'\Type\Exception\UnsupportedAttribute');
    $this->_type->setTyphoonAttribute('foo', 'bar');
  }

  /**
   * @covers Typhoon\StandardDynamicType::setTyphoonAttribute
   * @covers Typhoon\StandardDynamicType::assertAttribute
   */
  public function testSetTyphoonAttributeFailureKeyType()
  {
    $this->setExpectedException(__NAMESPACE__.'\Assertion\Exception\UnexpectedArgument');
    $this->_type->setTyphoonAttribute(0, 'foo');
  }

  /**
   * @covers Typhoon\StandardDynamicType::hasAttribute
   * @covers Typhoon\StandardDynamicType::assertAttribute
   */
  public function testHasAttributeFailureUnsupported()
  {
    $this->setExpectedException(__NAMESPACE__.'\Type\Exception\UnsupportedAttribute');
    $this->_type->hasAttribute('foo');
  }

  /**
   * @covers Typhoon\StandardDynamicType::hasAttribute
   * @covers Typhoon\StandardDynamicType::assertAttribute
   */
  public function testHasAttributeFailureType()
  {
    $this->setExpectedException(__NAMESPACE__.'\Assertion\Exception\UnexpectedArgument');
    $this->_type->hasAttribute(0);
  }

  /**
   * @covers Typhoon\StandardDynamicType::attribute
   * @covers Typhoon\StandardDynamicType::assertAttribute
   */
  public function testAttributeFailureUnsupported()
  {
    $this->setExpectedException(__NAMESPACE__.'\Type\Exception\UnsupportedAttribute');
    $this->_type->attribute('foo');
  }

  /**
   * @covers Typhoon\StandardDynamicType::attribute
   * @covers Typhoon\StandardDynamicType::assertAttribute
   */
  public function testAttributeFailureType()
  {
    $this->setExpectedException(__NAMESPACE__.'\Assertion\Exception\UnexpectedArgument');
    $this->_type->attribute(0);
  }

  /**
   * @var StandardDynamicType
   */
  protected $_type;
}