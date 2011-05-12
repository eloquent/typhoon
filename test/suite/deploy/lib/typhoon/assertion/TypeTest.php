<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Typhoon\Assertion;

use stdClass;
use Typhoon\Test\TestCase;
use Typhoon\Type\Mixed;

class TypeTest extends TestCase
{
  protected function setUp()
  {
    parent::setUp();
    
    $this->_assertion = new Type;
  }

  /**
   * @covers Typhoon\Assertion\Type::__construct
   * @covers Typhoon\Assertion\Type::assert
   */
  public function testAssertion()
  {
    $value = 'foo';
    $type = $this->getMock('Typhoon\Type');
    $type
      ->expects($this->once())
      ->method('check')
      ->with($this->equalTo($value))
      ->will($this->returnValue(true))
    ;

    $assertion = new Type;
    $assertion->setType($type);
    $assertion->setValue($value);
    $assertion->assert();
  }

  /**
   * @covers Typhoon\Assertion\Type::__construct
   * @covers Typhoon\Assertion\Type::assert
   */
  public function testAssertionFailure()
  {
    $value = 'foo';
    $type = $this->getMock('Typhoon\Type');
    $type
      ->expects($this->once())
      ->method('check')
      ->with($this->equalTo($value))
      ->will($this->returnValue(false))
    ;

    $assertion = new Type;
    $assertion->setType($type);
    $assertion->setValue($value);

    $this->setExpectedException(__NAMESPACE__.'\Exception\UnexpectedType');
    $assertion->assert();
  }
  
  /**
   * @covers Typhoon\Assertion\Type::setType
   * @covers Typhoon\Assertion\Type::type
   */
  public function testType()
  {
    $this->assertEquals(new Mixed, $this->_assertion->type());

    $type = $this->getMock('Typhoon\Type');
    $this->_assertion->setType($type);

    $this->assertSame($type, $this->_assertion->type());
  }

  /**
   * @covers Typhoon\Assertion\Type::setValue
   * @covers Typhoon\Assertion\Type::value
   */
  public function testValue()
  {
    $this->assertNull($this->_assertion->value());

    $value = new stdClass;
    $this->_assertion->setValue($value);

    $this->assertSame($value, $this->_assertion->value());
  }

  public function testImplementsAssertion()
  {
    $this->assertInstanceOf('Typhoon\Assertion', new Type);
  }

  /**
   * @var Type
   */
  protected $_assertion;
}