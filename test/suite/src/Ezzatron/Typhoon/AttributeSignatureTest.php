<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ezzatron\Typhoon;

use Phake;
use Ezzatron\Typhoon\Test\TestCase;

class AttributeSignatureTest extends TestCase
{
  /**
   * @return array
   */
  public function typeFailureData()
  {
    $type = Phake::mock(__NAMESPACE__.'\Type');

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
   * @covers Ezzatron\Typhoon\AttributeSignature
   */
  public function testSignature()
  {
    $type = Phake::mock(__NAMESPACE__.'\Type');
    $this->_signature['foo'] = $type;

    $this->assertEquals($type, $this->_signature['foo']);
  }

  /**
   * @covers Ezzatron\Typhoon\AttributeSignature
   * @dataProvider typeFailureData
   */
  public function testTypeFailures($method)
  {
    $arguments = func_get_args();
    array_shift($arguments);

    $this->setExpectedException('Ezzatron\Typhoon\Assertion\Exception\UnexpectedType');
    call_user_func_array(array($this->_signature, $method), $arguments);
  }
  
  /**
   * @var AttributeSignature
   */
  protected $_signature;
}