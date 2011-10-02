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

use Ezzatron\Typhoon\Typhoon;
use Ezzatron\Typhoon\Parameter\Parameter;
use Ezzatron\Typhoon\Parameter\ParameterList\ParameterList;
use Ezzatron\Typhoon\Primitive\Integer;
use Ezzatron\Typhoon\Primitive\String;
use Phake;
use stdClass;
use ReflectionObject;

class ParameterAssertionTest extends \Ezzatron\Typhoon\Test\TestCase
{
  protected function setUp()
  {
    parent::setUp();
    
    $this->_assertion = new ParameterAssertion;
    $this->_parameter = new Parameter;
  }

  /**
   * @covers Ezzatron\Typhoon\Assertion\ParameterAssertion::assert
   */
  public function testAssertUseTypeAssertion()
  {
    $typeAssertion = Phake::mock(__NAMESPACE__.'\TypeAssertion');

    $assertion = Phake::partialMock(__NAMESPACE__.'\ParameterAssertion');
    Phake::when($assertion)->typeAssertion()
      ->thenReturn($typeAssertion)
    ;
    $assertion->setParameter($this->_parameter);
    $assertion->setValue('foo');

    $assertion->assert();

    Phake::verify($typeAssertion)->assert();

    $this->assertTrue(true);
  }

  /**
   * @covers Ezzatron\Typhoon\Assertion\ParameterAssertion::assert
   */
  public function testAssertFailureType()
  {
    $type = Phake::mock('Ezzatron\Typhoon\Type\Type');
    Phake::when($type)->typhoonCheck()->thenReturn(false);

    $parameter = clone $this->_parameter;
    $parameter->setType($type);
    $parameter->setName(new String('foo'));

    $this->_assertion->setParameter($parameter);
    $this->_assertion->setIndex(new Integer(666));

    $this->setExpectedException(__NAMESPACE__.'\Exception\UnexpectedArgumentException', "Unexpected argument of type 'null' at index 666 (foo)");
    $this->_assertion->assert();
  }

  /**
   * @covers Ezzatron\Typhoon\Assertion\ParameterAssertion::setParameter
   * @covers Ezzatron\Typhoon\Assertion\ParameterAssertion::parameter
   */
  public function testParameter()
  {
    $this->assertEquals(new Parameter, $this->_assertion->parameter());

    $this->_assertion->setParameter($this->_parameter);

    $this->assertSame($this->_parameter, $this->_assertion->parameter());
  }

  /**
   * @covers Ezzatron\Typhoon\Assertion\ParameterAssertion::setValue
   * @covers Ezzatron\Typhoon\Assertion\ParameterAssertion::value
   */
  public function testValue()
  {
    $this->assertNull($this->_assertion->value());

    $value = new stdClass;
    $this->_assertion->setValue($value);

    $this->assertSame($value, $this->_assertion->value());
  }

  /**
   * @covers Ezzatron\Typhoon\Assertion\ParameterAssertion::setIndex
   * @covers Ezzatron\Typhoon\Assertion\ParameterAssertion::index
   */
  public function testIndex()
  {
    $this->assertEquals(0, $this->_assertion->index());

    $this->_assertion->setIndex(new Integer(666));

    $this->assertEquals(666, $this->_assertion->index());
  }

  /**
   * @covers Ezzatron\Typhoon\Assertion\ParameterAssertion::typeAssertion
   */
  public function testTypeAssertion()
  {
    $type = Phake::mock('Ezzatron\Typhoon\Type\Type');
    $value = 'foo';

    $parameter = new Parameter;
    $parameter->setType($type);

    $assertion = new ParameterAssertion;
    $assertion->setParameter($parameter);
    $assertion->setValue($value);

    $expected = new TypeAssertion;
    $expected->setType($type);
    $expected->setValue($value);

    $reflector = new ReflectionObject($assertion);
    $method = $reflector->getMethod('typeAssertion');
    $method->setAccessible(true);

    $this->assertEquals($expected, $method->invoke($assertion));
  }

  /**
   * @covers Ezzatron\Typhoon\Assertion\ParameterAssertion
   */
  public function testImplementsAssertion()
  {
    $this->assertInstanceOf(__NAMESPACE__.'\Assertion', $this->_assertion);
  }

  /**
   * @var ParameterAssertion
   */
  protected $_assertion;

  /**
   * @var Parameter
   */
  protected $_parameter;
}