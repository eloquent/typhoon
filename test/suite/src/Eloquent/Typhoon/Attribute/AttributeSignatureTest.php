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
use Phake;

class AttributeSignatureTest extends \Eloquent\Typhoon\Test\TestCase
{
  /**
   * @return array
   */
  public function typeFailureData()
  {
    $type = Phake::mock('Eloquent\Typhoon\Type\Type');

    return array(
      array('offsetSet', 'foo', 'bar'),
      array('offsetSet', 'foo', 1),
      array('offsetSet', 'foo', null),
      array('offsetSet', 'foo', true),
      array('offsetSet', 'foo', false),

      array('offsetSet', 1, $type),
      array('offsetSet', null, $type),
      array('offsetSet', true, $type),
      array('offsetSet', .1, $type),
    );
  }

  protected function setUp()
  {
    $this->_signature = new AttributeSignature;
  }

  /**
   * @covers Eloquent\Typhoon\Attribute\AttributeSignature::setHolderName
   * @covers Eloquent\Typhoon\Attribute\AttributeSignature::holderName
   * @group attribute
   * @group collection
   * @group core
   */
  public function testHolderName()
  {
    $this->assertNull($this->_signature->holderName());

    $this->_signature->setHolderName(new String('foo'));

    $this->assertEquals('foo', $this->_signature->holderName());
  }

  /**
   * @covers Eloquent\Typhoon\Attribute\AttributeSignature
   * @group attribute
   * @group collection
   * @group core
   */
  public function testSignature()
  {
    $type = Phake::mock('Eloquent\Typhoon\Type\Type');
    $this->_signature->set('foo', $type);

    $this->assertSame($type, $this->_signature['foo']);
    $this->assertFalse($this->_signature->isRequired('foo'));

    $type = Phake::mock('Eloquent\Typhoon\Type\Type');
    $this->_signature->set('foo', $type, new Boolean(true));

    $this->assertSame($type, $this->_signature['foo']);
    $this->assertTrue($this->_signature->isRequired('foo'));

    $this->_signature->remove('foo');

    $this->assertFalse($this->_signature->keyExists('foo'));
  }

  /**
   * @covers Eloquent\Typhoon\Attribute\AttributeSignature
   * @dataProvider typeFailureData
   * @group attribute
   * @group collection
   * @group core
   */
  public function testTypeFailure($method)
  {
    $arguments = func_get_args();
    array_shift($arguments);

    $this->setExpectedException('Eloquent\Typhoon\Assertion\Exception\UnexpectedArgumentException');
    call_user_func_array(array($this->_signature, $method), $arguments);
  }
  
  /**
   * @var AttributeSignature
   */
  protected $_signature;
}
