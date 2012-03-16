<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Attribute;

use Eloquent\Typhoon\Primitive\Boolean;
use Eloquent\Typhoon\Primitive\String;
use Eloquent\Typhoon\Type\StringType;

class AttributesTest extends \Eloquent\Typhoon\Test\TestCase
{
  protected function setUp()
  {
    parent::setUp();

    $this->_signature = new AttributeSignature;
    $this->_signature->setHolderName(new String('holder'));
    $this->_signature->set('foo', new StringType, new Boolean(true));
    $this->_signature->set('bar', new StringType);

    $this->_attributes = new Attributes;
    $this->_attributes['foo'] = 'baz';
  }

  /**
   * @covers Eloquent\Typhoon\Attribute\Attributes::adapt
   * @group attribute
   * @group collection
   * @group core
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
   * @covers Eloquent\Typhoon\Attribute\Attributes::adapt
   * @group attribute
   * @group collection
   * @group core
   */
  public function testAdaptFailure()
  {
    $this->setExpectedException('Eloquent\Typhoon\Assertion\Exception\UnexpectedArgumentException');
    Attributes::adapt('foo');
  }

  /**
   * @covers Eloquent\Typhoon\Attribute\Attributes::setSignature
   * @covers Eloquent\Typhoon\Attribute\Attributes::signature
   * @covers Eloquent\Typhoon\Attribute\Attributes::assert
   * @group attribute
   * @group collection
   * @group core
   */
  public function testSignature()
  {
    $this->assertNull($this->_attributes->signature());

    $this->_attributes->setSignature($this->_signature);

    $this->assertEquals($this->_signature, $this->_attributes->signature());
  }

  /**
   * @covers Eloquent\Typhoon\Attribute\Attributes::setSignature
   * @covers Eloquent\Typhoon\Attribute\Attributes::signature
   * @covers Eloquent\Typhoon\Attribute\Attributes::assert
   * @covers Eloquent\Typhoon\Attribute\Attributes::assertValue
   * @group attribute
   * @group collection
   * @group core
   */
  public function testSignatureFailureType()
  {
    $this->_attributes->set('foo', null);

    $this->setExpectedException('Eloquent\Typhoon\Assertion\Exception\UnexpectedAttributeException');
    $this->_attributes->setSignature($this->_signature);
  }

  /**
   * @covers Eloquent\Typhoon\Attribute\Attributes::setSignature
   * @covers Eloquent\Typhoon\Attribute\Attributes::signature
   * @covers Eloquent\Typhoon\Attribute\Attributes::assert
   * @group attribute
   * @group collection
   * @group core
   */
  public function testSignatureFailureRequired()
  {
    $this->_attributes->remove('foo');

    $this->setExpectedException('Eloquent\Typhoon\Assertion\Exception\MissingAttributeException');
    $this->_attributes->setSignature($this->_signature);
  }

  /**
   * @covers Eloquent\Typhoon\Attribute\Attributes::finalize
   * @covers Eloquent\Typhoon\Attribute\Attributes::finalized
   * @covers Eloquent\Typhoon\Attribute\Attributes::__clone
   * @group attribute
   * @group collection
   * @group core
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
   * @covers Eloquent\Typhoon\Attribute\Attributes::set
   * @group attribute
   * @group collection
   * @group core
   */
  public function testFinalizedFailureSet()
  {
    $this->_attributes->finalize();

    $this->setExpectedException('Eloquent\Typhoon\Attribute\Exception\FinalizedException');
    $this->_attributes->set('foo', 'bar');
  }

  /**
   * @covers Eloquent\Typhoon\Attribute\Attributes::remove
   * @group attribute
   * @group collection
   * @group core
   */
  public function testFinalizedFailureRemove()
  {
    $this->_attributes->finalize();

    $this->setExpectedException('Eloquent\Typhoon\Attribute\Exception\FinalizedException');
    $this->_attributes->remove('foo');
  }

  /**
   * @covers Eloquent\Typhoon\Attribute\Attributes::remove
   * @group attribute
   * @group collection
   * @group core
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
   * @covers Eloquent\Typhoon\Attribute\Attributes::remove
   * @group attribute
   * @group collection
   * @group core
   */
  public function testRemoveFailureRequired()
  {
    $this->_attributes['foo'] = 'baz';
    $this->_attributes->setSignature($this->_signature);

    $this->setExpectedException('Eloquent\Typhoon\Assertion\Exception\MissingAttributeException');
    $this->_attributes->remove('foo');
  }

  /**
   * @covers Eloquent\Typhoon\Attribute\Attributes
   * @group attribute
   * @group collection
   * @group core
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

  /**
   * @covers Eloquent\Typhoon\Attribute\Attributes
   * @dataProvider unsupportedAttributeData
   * @group attribute
   * @group collection
   * @group core
   */
  public function testUnsupportedAttribute($method)
  {
    $arguments = func_get_args();
    array_shift($arguments);

    $this->_attributes->setSignature($this->_signature);

    $this->setExpectedException('Eloquent\Typhoon\Assertion\Exception\UnsupportedAttributeException');
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
