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
use Ezzatron\Typhoon\Primitive\Boolean;
use Ezzatron\Typhoon\Primitive\Integer;
use Ezzatron\Typhoon\Primitive\String;
use Phake;
use ReflectionObject;

class ParameterListAssertionTest extends \Ezzatron\Typhoon\Test\TestCase
{
  protected function setUp()
  {
    parent::setUp();
    
    $this->_assertion = new ParameterListAssertion;
    $this->_parameterList = new ParameterList;
    $this->_parameter = new Parameter;
    $this->_parameter->setName(new String('parameter'));
  }

  /**
   * @covers Ezzatron\Typhoon\Assertion\ParameterListAssertion::assert
   */
  public function testAssertUseParameterAssertion()
  {
    $argument_0 = 'foo';
    $argument_1 = 'bar';
    $arguments = array($argument_0, $argument_1);

    $parameter_0 = Phake::mock('Ezzatron\Typhoon\Parameter\Parameter');
    $parameter_1 = Phake::mock('Ezzatron\Typhoon\Parameter\Parameter');

    $assertion_0 = Phake::mock(__NAMESPACE__.'\ParameterAssertion');
    $assertion_1 = Phake::mock(__NAMESPACE__.'\ParameterAssertion');

    $parameterList = new ParameterList;
    $parameterList[] = $parameter_0;
    $parameterList[] = $parameter_1;

    $assertion = Phake::partialMock(__NAMESPACE__.'\ParameterListAssertion');
    Phake::when($assertion)->parameterAssertion(Phake::anyParameters())
      ->thenReturn($assertion_0)
      ->thenReturn($assertion_1)
    ;

    $assertion->setParameterList($parameterList);
    $assertion->setArguments($arguments);

    $assertion->assert();

    Phake::inOrder(
      Phake::verify($assertion)->parameterAssertion($this->identicalTo($parameter_0), $argument_0, new Integer(0))
      , Phake::verify($assertion_0)->assert()
      , Phake::verify($assertion)->parameterAssertion($this->identicalTo($parameter_1), $argument_1, new Integer(1))
      , Phake::verify($assertion_1)->assert()
    );

    $this->assertTrue(true);
  }

  /**
   * @covers Ezzatron\Typhoon\Assertion\ParameterListAssertion::assert
   */
  public function testAssertFixedLength()
  {
    $value = 'foo';
    $arguments = array();
    $this->_assertion->setParameterList($this->_parameterList);

    $this->_assertion->setArguments($arguments);
    $this->_assertion->assert();

    $arguments[] = $value;
    $this->_assertion->setArguments($arguments);
    $this->_parameterList[] = $this->_parameter;

    $this->_assertion->assert($arguments);

    $arguments[] = $value;
    $this->_assertion->setArguments($arguments);
    $this->_parameterList[] = $this->_parameter;

    $this->_assertion->assert($arguments);
    
    $this->assertTrue(true);
  }

  /**
   * @covers Ezzatron\Typhoon\Assertion\ParameterListAssertion::assert
   */
  public function testAssertOptional()
  {
    $value = 'foo';
    $arguments = array();
    $optionalParameter = $this->_parameter;
    $optionalParameter->setOptional(new Boolean(true));
    $this->_assertion->setParameterList($this->_parameterList);

    $arguments[] = $value;
    $this->_assertion->setArguments($arguments);
    $this->_parameterList[] = $this->_parameter;
    $this->_parameterList[] = $optionalParameter;

    $this->_assertion->assert();

    $this->_parameterList[] = $optionalParameter;

    $this->_assertion->assert();
    
    $this->assertTrue(true);
  }

  /**
   * @covers Ezzatron\Typhoon\Assertion\ParameterListAssertion::assert
   */
  public function testAssertVariableLength()
  {
    $value = 'foo';
    $arguments = array();
    $this->_parameterList->setVariableLength(new Boolean(true));
    $this->_assertion->setParameterList($this->_parameterList);

    $arguments[] = $value;
    $arguments[] = $value;
    $this->_assertion->setArguments($arguments);
    $this->_parameterList[] = $this->_parameter;

    $this->_assertion->assert();

    $this->assertTrue(true);
  }

  /**
   * @covers Ezzatron\Typhoon\Assertion\ParameterListAssertion::assert
   */
  public function testAssertFailureType()
  {
    $this->_assertion->setParameterList($this->_parameterList);

    $type = Phake::mock('Ezzatron\Typhoon\Type\Type');
    Phake::when($type)->typhoonCheck()->thenReturn(false);

    $parameter = clone $this->_parameter;
    $parameter->setType($type);
    $this->_parameterList[] = $parameter;

    $this->_assertion->setArguments(array(null));

    $this->setExpectedException(__NAMESPACE__.'\Exception\UnexpectedArgumentException', 'at index 0');
    $this->_assertion->assert();
  }

  /**
   * @covers Ezzatron\Typhoon\Assertion\ParameterListAssertion::assert
   */
  public function testAssertFailureNoArguments()
  {
    $this->_assertion->setParameterList($this->_parameterList);
    $this->_parameterList[] = $this->_parameter;

    $this->setExpectedException(__NAMESPACE__.'\Exception\MissingArgumentException', 'at index 0');
    $this->_assertion->assert();
  }

  /**
   * @covers Ezzatron\Typhoon\Assertion\ParameterListAssertion::assert
   */
  public function testAssertFailureNotEnoughArguments()
  {
    $this->_assertion->setParameterList($this->_parameterList);
    $this->_parameterList[] = $this->_parameter;
    $this->_parameterList[] = $this->_parameter;
    $this->_assertion->setArguments(array(null));

    $this->setExpectedException(__NAMESPACE__.'\Exception\MissingArgumentException', 'at index 1');
    $this->_assertion->assert();
  }

  /**
   * @covers Ezzatron\Typhoon\Assertion\ParameterListAssertion::assert
   */
  public function testAssertFailureTooManyArguments()
  {
    $this->_assertion->setArguments(array(null));

    $this->setExpectedException(__NAMESPACE__.'\Exception\UnexpectedArgumentException', 'at index 0');
    $this->_assertion->assert();
  }

  /**
   * @covers Ezzatron\Typhoon\Assertion\ParameterListAssertion::assert
   */
  public function testAssertFailureTooManyArgumentsNonEmpty()
  {
    $this->_assertion->setParameterList($this->_parameterList);
    $this->_parameterList[] = $this->_parameter;

    $this->_assertion->setArguments(array(null, null));

    $this->setExpectedException(__NAMESPACE__.'\Exception\UnexpectedArgumentException', 'at index 1');
    $this->_assertion->assert();
  }

  /**
   * @covers Ezzatron\Typhoon\Assertion\ParameterListAssertion::parameterAssertion
   */
  public function testParameterAssertion()
  {
    $assertion = new ParameterListAssertion;

    $expected = new ParameterAssertion;
    $expected->setParameter($this->_parameter);
    $expected->setValue('foo');
    $expected->setIndex(new Integer(666));

    $reflector = new ReflectionObject($assertion);
    $method = $reflector->getMethod('parameterAssertion');
    $method->setAccessible(true);

    $this->assertEquals($expected, $method->invokeArgs($assertion, array($this->_parameter, 'foo', new Integer(666))));
  }

  /**
   * @covers Ezzatron\Typhoon\Assertion\ParameterListAssertion::setParameterList
   * @covers Ezzatron\Typhoon\Assertion\ParameterListAssertion::parameterList
   */
  public function testParameterList()
  {
    $this->assertEquals(new ParameterList, $this->_assertion->parameterList());

    $parameterList = new ParameterList;
    $this->_assertion->setParameterList($parameterList);

    $this->assertSame($parameterList, $this->_assertion->parameterList());
  }

  /**
   * @covers Ezzatron\Typhoon\Assertion\ParameterListAssertion::setArguments
   * @covers Ezzatron\Typhoon\Assertion\ParameterListAssertion::arguments
   */
  public function testArguments()
  {
    $this->assertEquals(array(), $this->_assertion->arguments());

    $arguments = array('foo');
    $this->_assertion->setArguments($arguments);

    $this->assertSame($arguments, $this->_assertion->arguments());
  }

  /**
   * @covers Ezzatron\Typhoon\Assertion\ParameterListAssertion::setArguments
   */
  public function testSetArgumentsFailure()
  {
    $this->setExpectedException(__NAMESPACE__.'\Exception\UnexpectedArgumentException', "expected 'integer'");
    $this->_assertion->setArguments(array('foo' => 'foo'));
  }

  /**
   * @covers Ezzatron\Typhoon\Assertion\ParameterListAssertion
   */
  public function testImplementsAssertion()
  {
    $this->assertInstanceOf(__NAMESPACE__.'\Assertion', new ParameterListAssertion);
  }

  /**
   * @var ParameterListAssertion
   */
  protected $_assertion;

  /**
   * @var ParameterList
   */
  protected $_parameterList;

  /**
   * @var Parameter
   */
  protected $_parameter;
}