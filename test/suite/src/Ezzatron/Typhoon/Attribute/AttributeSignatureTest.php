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
use Phake;

class AttributeSignatureTest extends \Ezzatron\Typhoon\Test\TestCase
{
  /**
   * @return array
   */
  public function typeFailureData()
  {
    $type = Phake::mock('Ezzatron\Typhoon\Type\Type');

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
   * @covers Ezzatron\Typhoon\Attribute\AttributeSignature::setHolder
   * @covers Ezzatron\Typhoon\Attribute\AttributeSignature::holder
   */
  public function testHolder()
  {
    $this->assertNull($this->_signature->holder());

    $this->_signature->setHolder(new String('foo'));

    $this->assertEquals('foo', $this->_signature->holder());
  }

  /**
   * @covers Ezzatron\Typhoon\Attribute\AttributeSignature
   */
  public function testSignature()
  {
    $type = Phake::mock('Ezzatron\Typhoon\Type\Type');
    $this->_signature->set('foo', $type);

    $this->assertSame($type, $this->_signature['foo']);
    $this->assertFalse($this->_signature->isRequired('foo'));

    $type = Phake::mock('Ezzatron\Typhoon\Type\Type');
    $this->_signature->set('foo', $type, new Boolean(true));

    $this->assertSame($type, $this->_signature['foo']);
    $this->assertTrue($this->_signature->isRequired('foo'));

    $this->_signature->remove('foo');

    $this->assertFalse($this->_signature->keyExists('foo'));
  }

  /**
   * @covers Ezzatron\Typhoon\Attribute\AttributeSignature
   * @dataProvider typeFailureData
   */
  public function testTypeFailure($method)
  {
    $arguments = func_get_args();
    array_shift($arguments);

    $this->setExpectedException('Ezzatron\Typhoon\Assertion\Exception\UnexpectedArgumentException');
    call_user_func_array(array($this->_signature, $method), $arguments);
  }
  
  /**
   * @var AttributeSignature
   */
  protected $_signature;
}