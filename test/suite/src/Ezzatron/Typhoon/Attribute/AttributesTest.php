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
use Ezzatron\Typhoon\Primitive\Boolean;
use Ezzatron\Typhoon\Primitive\String;
use Ezzatron\Typhoon\Type\StringType;

class AttributesTest extends \Ezzatron\Typhoon\Test\TestCase
{
  /**
   * @return array
   */
  public function unsupportedAttributeData()
  {
    return array(
      array('offsetExists', 'baz'),
      array('offsetGet', 'baz'),
      array('offsetSet', 'baz', 'splat'),
      array('offsetUnset', 'baz'),
    );
  }

  protected function setUp()
  {
    $this->_signature = new AttributeSignature;
    $this->_signature->setHolder(new String('holder'));
    $this->_signature->set('foo', new StringType, new Boolean(true));
    $this->_signature->set('bar', new StringType);

    $this->_attributes = new Attributes;
    $this->_attributes['foo'] = 'baz';
  }

  /**
   * @covers Ezzatron\Typhoon\Attribute\Attributes::setSignature
   * @covers Ezzatron\Typhoon\Attribute\Attributes::signature
   * @covers Ezzatron\Typhoon\Attribute\Attributes::assert
   */
  public function testSignature()
  {
    $this->assertNull($this->_attributes->signature());

    $this->_attributes->setSignature($this->_signature);

    $this->assertEquals($this->_signature, $this->_attributes->signature());
  }

  /**
   * @covers Ezzatron\Typhoon\Attribute\Attributes::setSignature
   * @covers Ezzatron\Typhoon\Attribute\Attributes::signature
   * @covers Ezzatron\Typhoon\Attribute\Attributes::assert
   */
  public function testSignatureFailureType()
  {
    $this->_attributes->set('foo', null);

    $this->setExpectedException('Ezzatron\Typhoon\Assertion\Exception\UnexpectedArgumentException');
    $this->_attributes->setSignature($this->_signature);
  }

  /**
   * @covers Ezzatron\Typhoon\Attribute\Attributes::setSignature
   * @covers Ezzatron\Typhoon\Attribute\Attributes::signature
   * @covers Ezzatron\Typhoon\Attribute\Attributes::assert
   */
  public function testSignatureFailureRequired()
  {
    $this->_attributes->remove('foo');

    $this->setExpectedException(__NAMESPACE__ . '\Exception\RequiredAttributeException');
    $this->_attributes->setSignature($this->_signature);
  }

  /**
   * @covers Ezzatron\Typhoon\Attribute\Attributes::remove
   */
  public function testRemove()
  {
    $this->_attributes['foo'] = 'baz';
    $this->_attributes['bar'] = 'qux';

    $this->assertTrue($this->_attributes->exists('foo'));
    $this->assertTrue($this->_attributes->exists('bar'));
    
    $this->_attributes->remove('foo');
    $this->_attributes->remove('bar');

    $this->assertFalse($this->_attributes->exists('foo'));
    $this->assertFalse($this->_attributes->exists('bar'));

    $this->_attributes['foo'] = 'baz';
    $this->_attributes['bar'] = 'qux';
    $this->_attributes->setSignature($this->_signature);

    $this->assertTrue($this->_attributes->exists('foo'));
    $this->assertTrue($this->_attributes->exists('bar'));

    $this->_attributes->remove('bar');

    $this->assertFalse($this->_attributes->exists('bar'));
  }

  /**
   * @covers Ezzatron\Typhoon\Attribute\Attributes::remove
   */
  public function testRemoveFailureRequired()
  {
    $this->_attributes['foo'] = 'baz';
    $this->_attributes->setSignature($this->_signature);

    $this->setExpectedException(__NAMESPACE__ . '\Exception\RequiredAttributeException');
    $this->_attributes->remove('foo');
  }

  /**
   * @covers Ezzatron\Typhoon\Attribute\Attributes
   */
  public function testAttributes()
  {
    $this->_attributes['foo'] = 'bar';

    $this->assertEquals('bar', $this->_attributes['foo']);

    $this->_attributes->setSignature($this->_signature);
    $this->_attributes['foo'] = 'baz';

    $this->assertEquals('baz', $this->_attributes['foo']);
  }

  /**
   * @covers Ezzatron\Typhoon\Attribute\Attributes
   * @dataProvider unsupportedAttributeData
   */
  public function testUnsupportedAttribute($method)
  {
    $arguments = func_get_args();
    array_shift($arguments);

    $this->_attributes->setSignature($this->_signature);

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