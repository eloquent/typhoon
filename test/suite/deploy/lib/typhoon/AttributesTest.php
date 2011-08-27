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
use Typhoon\Type\String as StringType;

class AttributesTest extends TestCase
{
  /**
   * @return array
   */
  public function undefinedAttributeData()
  {
    return array(
      array('offsetExists', 'bar'),
      array('offsetGet', 'bar'),
      array('offsetSet', 'bar', 'baz'),
      array('offsetUnset', 'bar'),
    );
  }

  protected function setUp()
  {
    $this->_signature = new AttributeSignature;
    $this->_signature['foo'] = new StringType;

    $this->_attributes = new Attributes;
    $this->_attributes->setSignature($this->_signature);
  }

  /**
   * @covers Typhoon\Attributes::setSignature
   * @covers Typhoon\Attributes::signature
   */
  public function testSignature()
  {
    $attributes = new Attributes;

    $this->assertInstanceOf('Typhoon\AttributeSignature', $attributes->signature());

    $attributes->setSignature($this->_signature);

    $this->assertSame($this->_signature, $attributes->signature());
  }

  /**
   * @covers Typhoon\Attributes
   */
  public function testAttributes()
  {
    $this->_attributes['foo'] = 'bar';

    $this->assertEquals('bar', $this->_attributes['foo']);
  }

  /**
   * @covers Typhoon\Attributes
   * @dataProvider undefinedAttributeData
   */
  public function testUndefinedAttribute($method)
  {
    $arguments = func_get_args();
    array_shift($arguments);

    $this->setExpectedException('Typhoon\Attributes\Exception\UnsupportedAttribute');
    call_user_func_array(array($this->_attributes, $method), $arguments);
  }

  /**
   * @var Attributes
   */
  protected $_attributes;

  /**
   * @var AttributeSignature
   */
  protected $_signature;
}