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

use Typhoon;
use Typhoon\Parameter;
use Typhoon\ParameterList as ParameterListObject;
use Typhoon\Primitive\Boolean;
use Typhoon\Test\TestCase;

class ParameterListTest extends TestCase
{
  protected function setUp()
  {
    parent::setUp();
    
    $this->_assertion = new ParameterList;
    $this->_parameterList = new ParameterListObject;
  }

  /**
   * @covers Typhoon\Assertion\ParameterList::assert
   */
  public function testAssertUseTypeAssertion()
  {
    $argument_0 = 'foo';
    $argument_1 = 'bar';
    $arguments = array($argument_0, $argument_1);

    $type_0 = $this->getMockForAbstractClass('Typhoon\Type', array(), uniqid('Mock_testAssertUseTypeAssertion_'));
    $type_1 = $this->getMockForAbstractClass('Typhoon\Type', array(), uniqid('Mock_testAssertUseTypeAssertion_'));

    $assertion_0 = $this->getMock(__NAMESPACE__.'\Type', array('assert', 'setType', 'setValue'));
    $assertion_0
      ->expects($this->once())
      ->method('setType')
      ->with($this->equalTo($type_0))
    ;
    $assertion_0
      ->expects($this->once())
      ->method('setValue')
      ->with($this->equalTo($argument_0))
    ;
    $assertion_0
      ->expects($this->once())
      ->method('assert')
    ;
    $assertion_1 = $this->getMock(__NAMESPACE__.'\Type', array('assert', 'setType', 'setValue'));
    $assertion_1
      ->expects($this->once())
      ->method('setType')
      ->with($this->equalTo($type_1))
    ;
    $assertion_1
      ->expects($this->once())
      ->method('setValue')
      ->with($this->equalTo($argument_1))
    ;
    $assertion_1
      ->expects($this->once())
      ->method('assert')
    ;

    $parameter_0 = new Parameter;
    $parameter_0->setType($type_0);
    $parameter_1 = new Parameter;
    $parameter_1->setType($type_1);

    $parameterList = new ParameterListObject;
    $parameterList[] = $parameter_0;
    $parameterList[] = $parameter_1;

    $assertion = $this->getMock(__NAMESPACE__.'\ParameterList', array('typeAssertion'));
    $assertion
      ->expects($this->exactly(2))
      ->method('typeAssertion')
      ->will($this->onConsecutiveCalls($assertion_0, $assertion_1));
    ;
    $assertion->setParameterList($parameterList);
    $assertion->setArguments($arguments);

    $assertion->assert();

    $this->assertTrue(true);
  }

  /**
   * @covers Typhoon\Assertion\ParameterList::assert
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
    $this->_parameterList[] = new Parameter;

    $this->_assertion->assert($arguments);

    $arguments[] = $value;
    $this->_assertion->setArguments($arguments);
    $this->_parameterList[] = new Parameter;

    $this->_assertion->assert($arguments);
    
    $this->assertTrue(true);
  }

  /**
   * @covers Typhoon\Assertion\ParameterList::assert
   */
  public function testAssertOptional()
  {
    $value = 'foo';
    $arguments = array();
    $optionalParameter = new Parameter;
    $optionalParameter->setOptional(new Boolean(true));
    $this->_assertion->setParameterList($this->_parameterList);

    $arguments[] = $value;
    $this->_assertion->setArguments($arguments);
    $this->_parameterList[] = new Parameter;
    $this->_parameterList[] = $optionalParameter;

    $this->_assertion->assert();

    $this->_parameterList[] = $optionalParameter;

    $this->_assertion->assert();
    
    $this->assertTrue(true);
  }

  /**
   * @covers Typhoon\Assertion\ParameterList::assert
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
    $this->_parameterList[] = new Parameter;

    $this->_assertion->assert();

    $this->assertTrue(true);
  }

  /**
   * @covers Typhoon\Assertion\ParameterList::assert
   */
  public function testAssertFailureType()
  {
    $this->_assertion->setParameterList($this->_parameterList);

    $type = $this->getMockForAbstractClass('Typhoon\Type');
    $type
      ->expects($this->once())
      ->method('check')
      ->will($this->returnValue(false))
    ;

    $parameter = new Parameter;
    $parameter->setType($type);
    $this->_parameterList[] = $parameter;

    $this->_assertion->setArguments(array(null));

    $this->setExpectedException(__NAMESPACE__.'\Exception\UnexpectedArgument', 'at index 0');
    $this->_assertion->assert();
  }

  /**
   * @covers Typhoon\Assertion\ParameterList::assert
   */
  public function testAssertFailureNoArguments()
  {
    $this->_assertion->setParameterList($this->_parameterList);
    $this->_parameterList[] = new Parameter;

    $this->setExpectedException(__NAMESPACE__.'\Exception\MissingArgument', 'at index 0');
    $this->_assertion->assert();
  }

  /**
   * @covers Typhoon\Assertion\ParameterList::assert
   */
  public function testAssertFailureNotEnoughArguments()
  {
    $this->_assertion->setParameterList($this->_parameterList);
    $this->_parameterList[] = new Parameter;
    $this->_parameterList[] = new Parameter;
    $this->_assertion->setArguments(array(null));

    $this->setExpectedException(__NAMESPACE__.'\Exception\MissingArgument', 'at index 1');
    $this->_assertion->assert();
  }

  /**
   * @covers Typhoon\Assertion\ParameterList::assert
   */
  public function testAssertFailureTooManyArguments()
  {
    $this->_assertion->setArguments(array(null));

    $this->setExpectedException(__NAMESPACE__.'\Exception\UnexpectedArgument', 'at index 0');
    $this->_assertion->assert();
  }

  /**
   * @covers Typhoon\Assertion\ParameterList::assert
   */
  public function testAssertFailureTooManyArgumentsNonEmpty()
  {
    $this->_assertion->setParameterList($this->_parameterList);
    $this->_parameterList[] = new Parameter;

    $this->_assertion->setArguments(array(null, null));

    $this->setExpectedException(__NAMESPACE__.'\Exception\UnexpectedArgument', 'at index 1');
    $this->_assertion->assert();
  }

  /**
   * @covers Typhoon\Assertion\ParameterList::typeAssertion
   */
  public function testTypeAssertion()
  {
    $expected = get_class(Typhoon::instance()->typeAssertion());

    $this->assertInstanceOf($expected, $this->_assertion->typeAssertion());
  }

  /**
   * @covers Typhoon\Assertion\ParameterList::setParameterList
   * @covers Typhoon\Assertion\ParameterList::parameterList
   */
  public function testParameterList()
  {
    $this->assertEquals(new ParameterListObject, $this->_assertion->parameterList());

    $parameterList = new ParameterListObject;
    $this->_assertion->setParameterList($parameterList);

    $this->assertSame($parameterList, $this->_assertion->parameterList());
  }

  /**
   * @covers Typhoon\Assertion\ParameterList::setArguments
   * @covers Typhoon\Assertion\ParameterList::arguments
   */
  public function testArguments()
  {
    $this->assertEquals(array(), $this->_assertion->arguments());

    $arguments = array('foo');
    $this->_assertion->setArguments($arguments);

    $this->assertSame($arguments, $this->_assertion->arguments());
  }

  /**
   * @covers Typhoon\Assertion\ParameterList::setArguments
   */
  public function testSetArgumentsFailure()
  {
    $this->setExpectedException(__NAMESPACE__.'\Exception\UnexpectedArgument', "expected 'integer'");
    $this->_assertion->setArguments(array('foo' => 'foo'));
  }

  public function testImplementsAssertion()
  {
    $this->assertInstanceOf('Typhoon\Assertion', new ParameterList);
  }

  /**
   * @var ParameterList
   */
  protected $_assertion;

  /**
   * @var ParameterListObject
   */
  protected $_parameterList;
}