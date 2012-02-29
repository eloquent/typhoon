<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Assertion;

use Phake;
use stdClass;
use Eloquent\Typhoon\Type\MixedType;

class TypeAssertionTest extends \Eloquent\Typhoon\Test\TestCase
{
  protected function setUp()
  {
    parent::setUp();
    
    $this->_assertion = new TypeAssertion;
  }

  /**
   * @covers Eloquent\Typhoon\Assertion\TypeAssertion::__construct
   * @covers Eloquent\Typhoon\Assertion\TypeAssertion::assert
   * @group assertion
   * @group core
   */
  public function testAssertion()
  {
    $value = 'foo';
    $type = Phake::mock('Eloquent\Typhoon\Type\Type');
    Phake::when($type)->typhoonCheck($value)->thenReturn(true);

    $assertion = new TypeAssertion;
    $assertion->setType($type);
    $assertion->setValue($value);
    $assertion->assert();

    Phake::verify($type)->typhoonCheck($value);

    $this->assertTrue(true);
  }

  /**
   * @covers Eloquent\Typhoon\Assertion\TypeAssertion::__construct
   * @covers Eloquent\Typhoon\Assertion\TypeAssertion::assert
   * @group assertion
   * @group core
   */
  public function testAssertionFailure()
  {
    $value = 'foo';
    $type = Phake::mock('Eloquent\Typhoon\Type\Type');
    Phake::when($type)->typhoonCheck($value)->thenReturn(false);

    $assertion = new TypeAssertion;
    $assertion->setType($type);
    $assertion->setValue($value);

    $this->setExpectedException(__NAMESPACE__.'\Exception\UnexpectedTypeException');

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
   * @covers Eloquent\Typhoon\Assertion\TypeAssertion::setType
   * @covers Eloquent\Typhoon\Assertion\TypeAssertion::type
   * @group assertion
   * @group core
   */
  public function testType()
  {
    $this->assertEquals(new MixedType, $this->_assertion->type());

    $type = Phake::mock('Eloquent\Typhoon\Type\Type');
    $this->_assertion->setType($type);

    $this->assertSame($type, $this->_assertion->type());
  }

  /**
   * @covers Eloquent\Typhoon\Assertion\TypeAssertion::setValue
   * @covers Eloquent\Typhoon\Assertion\TypeAssertion::value
   * @group assertion
   * @group core
   */
  public function testValue()
  {
    $this->assertNull($this->_assertion->value());

    $value = new stdClass;
    $this->_assertion->setValue($value);

    $this->assertSame($value, $this->_assertion->value());
  }

  /**
   * @covers Eloquent\Typhoon\Assertion\TypeAssertion
   * @group assertion
   * @group core
   */
  public function testImplementsAssertion()
  {
    $this->assertInstanceOf(__NAMESPACE__.'\Assertion', new TypeAssertion);
  }

  /**
   * @var TypeAssertion
   */
  protected $_assertion;
}
