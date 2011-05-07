<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Typhoon;

use ArrayIterator;
use PHPUnit_Framework_TestCase;
use stdClass;
use Typhoon\Primitive\Boolean;

class ParameterListTest extends PHPUnit_Framework_TestCase
{
  /**
   * @return array
   */
  public function unexpectedArgumentData()
  {
    return array(
      array('offsetExists', array('foo')),              // #0: non-integer index
      array('offsetSet', array(0, $this->_parameter)),  // #1: non-null index
      array('offsetSet', array(null, null)),            // #2: non-parameter parameter
      array('offsetGet', array('foo')),                 // #3: non-integer index
    );
  }

  protected function setUp()
  {
    $this->_parameterList = new ParameterList;
    $this->_parameter = new Parameter;
  }

  /**
   * @covers Typhoon\ParameterList::assert
   */
  public function testAssertCallTypeAssert()
  {
    $arguments = array();

    $value_1 = 'foo';
    $arguments[] = $value_1;
    $type_1 = $this->getMock(__NAMESPACE__.'\Type', array('check', 'assert'));
    $type_1
      ->expects($this->once())
      ->method('assert')
      ->with($this->equalTo($value_1))
    ;
    $parameter_1 = new Parameter;
    $parameter_1->setType($type_1);

    $value_2 = 'bar';
    $arguments[] = $value_2;
    $arguments[] = $value_2;
    $type_2 = $this->getMock(__NAMESPACE__.'\Type', array('check', 'assert'));
    $type_2
      ->expects($this->exactly(2))
      ->method('assert')
      ->with($this->equalTo($value_2))
    ;
    $parameter_2 = new Parameter;
    $parameter_2->setType($type_2);

    $this->_parameterList[] = $parameter_1;
    $this->_parameterList[] = $parameter_2;
    $this->_parameterList->setVariableLength(new Boolean(true));

    $this->_parameterList->assert($arguments);
  }

  /**
   * @covers Typhoon\ParameterList::assert
   */
  public function testAssertFixedLength()
  {
    $value = 'foo';
    $arguments = array();

    $this->_parameterList->assert($arguments);

    $arguments[] = $value;
    $this->_parameterList[] = $this->_parameter;

    $this->_parameterList->assert($arguments);

    $arguments[] = $value;
    $this->_parameterList[] = $this->_parameter;

    $this->_parameterList->assert($arguments);
  }

  /**
   * @covers Typhoon\ParameterList::assert
   */
  public function testAssertOptional()
  {
    $value = 'foo';
    $arguments = array();
    $optionalParameter = new Parameter;
    $optionalParameter->setOptional(new Boolean(true));

    $arguments[] = $value;
    $this->_parameterList[] = $this->_parameter;
    $this->_parameterList[] = $optionalParameter;

    $this->_parameterList->assert($arguments);

    $this->_parameterList[] = $optionalParameter;

    $this->_parameterList->assert($arguments);
  }

  /**
   * @covers Typhoon\ParameterList::assert
   */
  public function testAssertVariableLength()
  {
    $value = 'foo';
    $arguments = array();

    $arguments[] = $value;
    $arguments[] = $value;
    $this->_parameterList[] = $this->_parameter;
    $this->_parameterList->setVariableLength(new Boolean(true));

    $this->_parameterList->assert($arguments);
  }

  /**
   * @covers Typhoon\ParameterList::assert
   */
  public function testAssertFailureType()
  {
    $type = $this->getMockForAbstractClass(__NAMESPACE__.'\Type');
    $type
      ->expects($this->once())
      ->method('check')
      ->will($this->returnValue(false))
    ;
    $this->_parameter->setType($type);
    $this->_parameterList[] = $this->_parameter;

    $this->setExpectedException(__NAMESPACE__.'\ParameterList\Exception\UnexpectedArgument', 'at index 0');
    $this->_parameterList->assert(array(null));
  }

  /**
   * @covers Typhoon\ParameterList::assert
   */
  public function testAssertFailureNoArguments()
  {
    $this->_parameterList[] = $this->_parameter;

    $this->setExpectedException(__NAMESPACE__.'\ParameterList\Exception\MissingArgument', 'at index 0');
    $this->_parameterList->assert(array());
  }

  /**
   * @covers Typhoon\ParameterList::assert
   */
  public function testAssertFailureNotEnoughArguments()
  {
    $this->_parameterList[] = $this->_parameter;
    $this->_parameterList[] = $this->_parameter;

    $this->setExpectedException(__NAMESPACE__.'\ParameterList\Exception\MissingArgument', 'at index 1');
    $this->_parameterList->assert(array(null));
  }

  /**
   * @covers Typhoon\ParameterList::assert
   */
  public function testAssertFailureTooManyArguments()
  {
    $this->setExpectedException(__NAMESPACE__.'\ParameterList\Exception\UnexpectedArgument', 'at index 0');
    $this->_parameterList->assert(array(null));
  }

  /**
   * @covers Typhoon\ParameterList::assert
   */
  public function testAssertFailureTooManyArgumentsNonEmpty()
  {
    $this->_parameterList[] = $this->_parameter;

    $this->setExpectedException(__NAMESPACE__.'\ParameterList\Exception\UnexpectedArgument', 'at index 1');
    $this->_parameterList->assert(array(null, null));
  }

  /**
   * @covers Typhoon\ParameterList::assert
   */
  public function testAssertFailureArgumentKey()
  {
    $this->_parameterList[] = $this->_parameter;

    $this->setExpectedException(__NAMESPACE__.'\ParameterList\Exception\UnexpectedArgument', "expected 'integer'");
    $this->_parameterList->assert(array('foo' => 'foo'));
  }

  /**
   * @covers Typhoon\ParameterList::setVariableLength
   * @covers Typhoon\ParameterList::variableLength
   */
  public function testVariableLength()
  {
    $this->assertFalse($this->_parameterList->variableLength());

    $this->_parameterList->setVariableLength(new Boolean(true));

    $this->assertTrue($this->_parameterList->variableLength());
  }

  /**
   * @covers Typhoon\ParameterList::offsetExists
   * @covers Typhoon\ParameterList::offsetSet
   * @covers Typhoon\ParameterList::offsetGet
   */
  public function testArrayAccess()
  {
    $this->assertInstanceOf('ArrayAccess', $this->_parameterList);

    $this->assertFalse(isset($this->_parameterList[0]));

    $this->_parameterList[] = $this->_parameter;

    $this->assertTrue(isset($this->_parameterList[0]));
    $this->assertSame($this->_parameter, $this->_parameterList[0]);

    $this->_parameterList[] = $this->_parameter;

    $this->assertTrue(isset($this->_parameterList[1]));
    $this->assertSame($this->_parameter, $this->_parameterList[1]);
  }

  /**
   * @covers Typhoon\ParameterList::offsetGet
   */
  public function testOffsetGetFailure()
  {
    $this->setExpectedException(__NAMESPACE__.'\ParameterList\Exception\UndefinedParameter');
    $this->_parameterList[0];
  }

  /**
   * @covers Typhoon\ParameterList::offsetUnset
   */
  public function testOffsetUnsetFailure()
  {
    $this->setExpectedException(__NAMESPACE__.'\Exception\NotImplemented');
    unset($this->_parameterList[0]);
  }

  /**
   * @covers Typhoon\ParameterList::offsetExists
   * @covers Typhoon\ParameterList::offsetSet
   * @covers Typhoon\ParameterList::offsetGet
   * @dataProvider unexpectedArgumentData
   */
  public function testUnexpectedArgumentFailure($method, array $arguments)
  {
    $this->setExpectedException(__NAMESPACE__.'\ParameterList\Exception\UnexpectedArgument');
    call_user_func_array(array($this->_parameterList, $method), $arguments);
  }

  /**
   * @covers Typhoon\ParameterList::offsetSet
   * @covers Typhoon\ParameterList::getIterator
   */
  public function testOffsetSetAndGetIterator()
  {
    $this->assertInstanceOf('Iterator', $this->_parameterList->getIterator());

    $expected = array();
    $this->assertEquals($expected, iterator_to_array($this->_parameterList->getIterator()));

    $this->_parameterList[] = $this->_parameter;

    $expected[] = $this->_parameter;
    $this->assertEquals($expected, iterator_to_array($this->_parameterList->getIterator()));
    
    $this->_parameterList[] = $this->_parameter;

    $expected[] = $this->_parameter;
    $this->assertEquals($expected, iterator_to_array($this->_parameterList->getIterator()));

    $iterations = 0;
    foreach ($this->_parameterList->getIterator() as $parameter)
    {
      $this->assertSame($this->_parameter, $parameter);

      $iterations++;
    }

    $this->assertEquals(2, $iterations);
  }

  public function testTraversable()
  {
    $this->assertInstanceOf('Traversable', $this->_parameterList);
  }

  /**
   * @var ParameterList
   */
  protected $_parameterList;

  /**
   * @var Parameter
   */
  protected $_parameter;
}