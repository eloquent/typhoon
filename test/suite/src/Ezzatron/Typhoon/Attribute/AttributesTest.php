<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ezzatron\Typhoon\Attribute;

use Phake;
use Ezzatron\Typhoon\Type\StringType;

class AttributesTest extends \Ezzatron\Typhoon\Test\TestCase
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
   * @covers Ezzatron\Typhoon\Attribute\Attributes::setSignature
   * @covers Ezzatron\Typhoon\Attribute\Attributes::signature
   */
  public function testSignature()
  {
    $attributes = new Attributes;

    $this->assertInstanceOf(__NAMESPACE__.'\AttributeSignature', $attributes->signature());

    $attributes->setSignature($this->_signature);

    $this->assertSame($this->_signature, $attributes->signature());
  }

  /**
   * @covers Ezzatron\Typhoon\Attribute\Attributes
   */
  public function testAttributes()
  {
    $this->_attributes['foo'] = 'bar';

    $this->assertEquals('bar', $this->_attributes['foo']);
  }

  /**
   * @covers Ezzatron\Typhoon\Attribute\Attributes
   * @dataProvider undefinedAttributeData
   */
  public function testUndefinedAttribute($method)
  {
    $arguments = func_get_args();
    array_shift($arguments);

    $this->setExpectedException(__NAMESPACE__.'\Exception\UnsupportedAttributeException');
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