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
use Ezzatron\Typhoon\Typhoon;
use Ezzatron\Typhoon\Parameter\Parameter;
use Ezzatron\Typhoon\Parameter\ParameterList\ParameterList;
use Ezzatron\Typhoon\Primitive\Boolean;

class ParameterListAssertionTest extends \Ezzatron\Typhoon\Test\TestCase
{
  protected function setUp()
  {
    parent::setUp();
    
    $this->_assertion = new ParameterListAssertion;
    $this->_parameterList = new ParameterList;
  }

  /**
   * @covers Ezzatron\Typhoon\Assertion\ParameterListAssertion::assert
   */
  public function testAssertUseTypeAssertion()
  {
    $argument_0 = 'foo';
    $argument_1 = 'bar';
    $arguments = array($argument_0, $argument_1);

    $type_0 = Phake::mock('Ezzatron\Typhoon\Type\Type');
    $type_1 = Phake::mock('Ezzatron\Typhoon\Type\Type');

    $assertion_0 = Phake::mock(__NAMESPACE__.'\TypeAssertion');
    $assertion_1 = Phake::mock(__NAMESPACE__.'\TypeAssertion');

    $parameter_0 = new Parameter;
    $parameter_0->setType($type_0);
    $parameter_1 = new Parameter;
    $parameter_1->setType($type_1);

    $parameterList = new ParameterList;
    $parameterList[] = $parameter_0;
    $parameterList[] = $parameter_1;

    $assertion = Phake::partialMock(__NAMESPACE__.'\ParameterListAssertion');
    Phake::when($assertion)->typeAssertion()
      ->thenReturn($assertion_0)
      ->thenReturn($assertion_1)
    ;

    $assertion->setParameterList($parameterList);
    $assertion->setArguments($arguments);

    $assertion->assert();

    Phake::inOrder(
      Phake::verify($assertion_0)->setType($this->identicalTo($type_0))
      , Phake::verify($assertion_0)->setValue($this->identicalTo($argument_0))
      , Phake::verify($assertion_0)->assert()
    );

    Phake::inOrder(
      Phake::verify($assertion_1)->setType($this->identicalTo($type_1))
      , Phake::verify($assertion_1)->setValue($this->identicalTo($argument_1))
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
    $this->_parameterList[] = new Parameter;

    $this->_assertion->assert($arguments);

    $arguments[] = $value;
    $this->_assertion->setArguments($arguments);
    $this->_parameterList[] = new Parameter;

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
   * @covers Ezzatron\Typhoon\Assertion\ParameterListAssertion::assert
   */
  public function testAssertVariableLength()
  {
    $value = 'foo';
    $arguments = array();
    $this->_parameterList->setVariableLength(true);
    $this->_assertion->setParameterList($this->_parameterList);

    $arguments[] = $value;
    $arguments[] = $value;
    $this->_assertion->setArguments($arguments);
    $this->_parameterList[] = new Parameter;

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

    $parameter = new Parameter;
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
    $this->_parameterList[] = new Parameter;

    $this->setExpectedException(__NAMESPACE__.'\Exception\MissingArgumentException', 'at index 0');
    $this->_assertion->assert();
  }

  /**
   * @covers Ezzatron\Typhoon\Assertion\ParameterListAssertion::assert
   */
  public function testAssertFailureNotEnoughArguments()
  {
    $this->_assertion->setParameterList($this->_parameterList);
    $this->_parameterList[] = new Parameter;
    $this->_parameterList[] = new Parameter;
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
    $this->_parameterList[] = new Parameter;

    $this->_assertion->setArguments(array(null, null));

    $this->setExpectedException(__NAMESPACE__.'\Exception\UnexpectedArgumentException', 'at index 1');
    $this->_assertion->assert();
  }

  /**
   * @covers Ezzatron\Typhoon\Assertion\ParameterListAssertion::typeAssertion
   */
  public function testTypeAssertion()
  {
    $expected = get_class(Typhoon::instance()->typeAssertion());

    $this->assertInstanceOf($expected, $this->_assertion->typeAssertion());
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
}