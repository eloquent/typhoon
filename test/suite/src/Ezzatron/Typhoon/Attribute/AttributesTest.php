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
    $this->_signature->setHolderName(new String('holder'));
    $this->_signature->set('foo', new StringType, new Boolean(true));
    $this->_signature->set('bar', new StringType);

    $this->_attributes = new Attributes;
    $this->_attributes['foo'] = 'baz';
  }

  /**
   * @covers Ezzatron\Typhoon\Attribute\Attributes::adapt
   */
  public function testAdapt()
  {
    $array = array(
      'foo' => 'bar',
      'baz' => 'qux',
    );
    $attributes = new Attributes($array);

    $this->assertEquals($attributes, Attributes::adapt($attributes));
    $this->assertNotSame($attributes, Attributes::adapt($attributes));
    $this->assertEquals($attributes, Attributes::adapt($array));
    $this->assertEquals(new Attributes, Attributes::adapt(null));
  }

  /**
   * @covers Ezzatron\Typhoon\Attribute\Attributes::adapt
   */
  public function testAdaptFailure()
  {
    $this->setExpectedException('Ezzatron\Typhoon\Assertion\Exception\UnexpectedArgumentException');
    Attributes::adapt('foo');
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
   * @covers Ezzatron\Typhoon\Attribute\Attributes::assertValue
   */
  public function testSignatureFailureType()
  {
    $this->_attributes->set('foo', null);

    $this->setExpectedException('Ezzatron\Typhoon\Assertion\Exception\UnexpectedAttributeException');
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

    $this->setExpectedException('Ezzatron\Typhoon\Assertion\Exception\MissingAttributeException');
    $this->_attributes->setSignature($this->_signature);
  }

  /**
   * @covers Ezzatron\Typhoon\Attribute\Attributes::finalize
   * @covers Ezzatron\Typhoon\Attribute\Attributes::finalized
   * @covers Ezzatron\Typhoon\Attribute\Attributes::__clone
   */
  public function testFinalized()
  {
    $this->assertFalse($this->_attributes->finalized());

    $this->_attributes->finalize();

    $this->assertTrue($this->_attributes->finalized());

    $this->_attributes = clone $this->_attributes;

    $this->assertFalse($this->_attributes->finalized());
  }

  /**
   * @covers Ezzatron\Typhoon\Attribute\Attributes::set
   */
  public function testFinalizedFailureSet()
  {
    $this->_attributes->finalize();

    $this->setExpectedException('Ezzatron\Typhoon\Attribute\Exception\FinalizedException');
    $this->_attributes->set('foo', 'bar');
  }

  /**
   * @covers Ezzatron\Typhoon\Attribute\Attributes::remove
   */
  public function testFinalizedFailureRemove()
  {
    $this->_attributes->finalize();

    $this->setExpectedException('Ezzatron\Typhoon\Attribute\Exception\FinalizedException');
    $this->_attributes->remove('foo');
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

    $this->setExpectedException('Ezzatron\Typhoon\Assertion\Exception\MissingAttributeException');
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

    $this->setExpectedException('Ezzatron\Typhoon\Assertion\Exception\UnsupportedAttributeException');
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