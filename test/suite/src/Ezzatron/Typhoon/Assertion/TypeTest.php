<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ezzatron\Typhoon\Assertion;

use Phake;
use stdClass;
use Ezzatron\Typhoon\Test\TestCase;
use Ezzatron\Typhoon\Type\Mixed;

class TypeTest extends TestCase
{
  protected function setUp()
  {
    parent::setUp();
    
    $this->_assertion = new Type;
  }

  /**
   * @covers Ezzatron\Typhoon\Assertion\Type::__construct
   * @covers Ezzatron\Typhoon\Assertion\Type::assert
   */
  public function testAssertion()
  {
    $value = 'foo';
    $type = Phake::mock('Ezzatron\Typhoon\Type');
    Phake::when($type)->typhoonCheck($value)->thenReturn(true);

    $assertion = new Type;
    $assertion->setType($type);
    $assertion->setValue($value);
    $assertion->assert();

    Phake::verify($type)->typhoonCheck($value);

    $this->assertTrue(true);
  }

  /**
   * @covers Ezzatron\Typhoon\Assertion\Type::__construct
   * @covers Ezzatron\Typhoon\Assertion\Type::assert
   */
  public function testAssertionFailure()
  {
    $value = 'foo';
    $type = Phake::mock('Ezzatron\Typhoon\Type');
    Phake::when($type)->typhoonCheck($value)->thenReturn(false);

    $assertion = new Type;
    $assertion->setType($type);
    $assertion->setValue($value);

    $this->setExpectedException(__NAMESPACE__.'\Exception\UnexpectedType');

    $e = null;
    try
    {
      $assertion->assert();
    }
    catch (Exception $e) {}

    Phake::verify($type)->typhoonCheck($value);

    if ($e) throw $e;
  }
  
  /**
   * @covers Ezzatron\Typhoon\Assertion\Type::setType
   * @covers Ezzatron\Typhoon\Assertion\Type::type
   */
  public function testType()
  {
    $this->assertEquals(new Mixed, $this->_assertion->type());

    $type = Phake::mock('Ezzatron\Typhoon\Type');
    $this->_assertion->setType($type);

    $this->assertSame($type, $this->_assertion->type());
  }

  /**
   * @covers Ezzatron\Typhoon\Assertion\Type::setValue
   * @covers Ezzatron\Typhoon\Assertion\Type::value
   */
  public function testValue()
  {
    $this->assertNull($this->_assertion->value());

    $value = new stdClass;
    $this->_assertion->setValue($value);

    $this->assertSame($value, $this->_assertion->value());
  }

  /**
   * @covers Ezzatron\Typhoon\Assertion\Type
   */
  public function testImplementsAssertion()
  {
    $this->assertInstanceOf('Ezzatron\Typhoon\Assertion', new Type);
  }

  /**
   * @var Type
   */
  protected $_assertion;
}