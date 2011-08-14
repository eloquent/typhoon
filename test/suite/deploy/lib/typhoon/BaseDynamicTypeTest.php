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

use Phake;
use Typhoon\Test\TestCase;

class BaseDynamicTypeTest extends TestCase
{
  protected function setUp()
  {
    parent::setUp();
    
    $this->_type = Phake::partialMock(__NAMESPACE__.'\BaseDynamicType');
    Phake::when($this->_type)->attributeSupported($this->anything())->thenReturn(false);
  }

  /**
   * @covers Typhoon\BaseDynamicType::setTyphoonAttribute
   * @covers Typhoon\BaseDynamicType::typhoonAttributes
   * @covers Typhoon\BaseDynamicType::hasAttribute
   * @covers Typhoon\BaseDynamicType::attribute
   * @covers Typhoon\BaseDynamicType::assertAttribute
   */
  public function testAttributes()
  {
    Phake::when($this->_type)->attributeSupported($this->anything())->thenReturn(true);

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
   * @covers Typhoon\BaseDynamicType::setTyphoonAttribute
   * @covers Typhoon\BaseDynamicType::assertAttribute
   */
  public function testSetTyphoonAttributeFailureUnsupported()
  {
    $this->setExpectedException(__NAMESPACE__.'\Type\Exception\UnsupportedAttribute');
    $this->_type->setTyphoonAttribute('foo', 'bar');
  }

  /**
   * @covers Typhoon\BaseDynamicType::setTyphoonAttribute
   * @covers Typhoon\BaseDynamicType::assertAttribute
   */
  public function testSetTyphoonAttributeFailureKeyType()
  {
    $this->setExpectedException(__NAMESPACE__.'\Assertion\Exception\UnexpectedArgument');
    $this->_type->setTyphoonAttribute(0, 'foo');
  }

  /**
   * @covers Typhoon\BaseDynamicType::hasAttribute
   * @covers Typhoon\BaseDynamicType::assertAttribute
   */
  public function testHasAttributeFailureUnsupported()
  {
    $this->setExpectedException(__NAMESPACE__.'\Type\Exception\UnsupportedAttribute');
    $this->_type->hasAttribute('foo');
  }

  /**
   * @covers Typhoon\BaseDynamicType::hasAttribute
   * @covers Typhoon\BaseDynamicType::assertAttribute
   */
  public function testHasAttributeFailureType()
  {
    $this->setExpectedException(__NAMESPACE__.'\Assertion\Exception\UnexpectedArgument');
    $this->_type->hasAttribute(0);
  }

  /**
   * @covers Typhoon\BaseDynamicType::attribute
   * @covers Typhoon\BaseDynamicType::assertAttribute
   */
  public function testAttributeFailureUnsupported()
  {
    $this->setExpectedException(__NAMESPACE__.'\Type\Exception\UnsupportedAttribute');
    $this->_type->attribute('foo');
  }

  /**
   * @covers Typhoon\BaseDynamicType::attribute
   * @covers Typhoon\BaseDynamicType::assertAttribute
   */
  public function testAttributeFailureType()
  {
    $this->setExpectedException(__NAMESPACE__.'\Assertion\Exception\UnexpectedArgument');
    $this->_type->attribute(0);
  }

  /**
   * @var BaseDynamicType
   */
  protected $_type;
}