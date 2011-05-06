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
    $this->_parameter_list = new ParameterList;
    $this->_parameter = new Parameter;
  }

  /**
   * @covers \Typhoon\ParameterList::assert
   */
  public function testAssertCallTypeAssert()
  {
    $arguments = array();

    $value_1 = 'foo';
    $arguments[] = $value_1;
    $type_1 = $this->getMock(__NAMESPACE__.'\Type', array('check', 'assert', '__toString'));
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
    $type_2 = $this->getMock(__NAMESPACE__.'\Type', array('check', 'assert', '__toString'));
    $type_2
      ->expects($this->exactly(2))
      ->method('assert')
      ->with($this->equalTo($value_2))
    ;
    $parameter_2 = new Parameter;
    $parameter_2->setType($type_2);

    $this->_parameter_list[] = $parameter_1;
    $this->_parameter_list[] = $parameter_2;
    $this->_parameter_list->setVariableLength(new Boolean(true));

    $this->_parameter_list->assert($arguments);
  }

  /**
   * @covers \Typhoon\ParameterList::assert
   */
  public function testAssertFixedLength()
  {
    $value = 'foo';
    $arguments = array();

    $this->_parameter_list->assert($arguments);

    $arguments[] = $value;
    $this->_parameter_list[] = $this->_parameter;

    $this->_parameter_list->assert($arguments);

    $arguments[] = $value;
    $this->_parameter_list[] = $this->_parameter;

    $this->_parameter_list->assert($arguments);
  }

  /**
   * @covers \Typhoon\ParameterList::assert
   */
  public function testAssertOptional()
  {
    $value = 'foo';
    $arguments = array();
    $optionalParameter = new Parameter;
    $optionalParameter->setOptional(new Boolean(true));

    $arguments[] = $value;
    $this->_parameter_list[] = $this->_parameter;
    $this->_parameter_list[] = $optionalParameter;

    $this->_parameter_list->assert($arguments);

    $this->_parameter_list[] = $optionalParameter;

    $this->_parameter_list->assert($arguments);
  }

  /**
   * @covers \Typhoon\ParameterList::assert
   */
  public function testAssertVariableLength()
  {
    $value = 'foo';
    $arguments = array();

    $arguments[] = $value;
    $arguments[] = $value;
    $this->_parameter_list[] = $this->_parameter;
    $this->_parameter_list->setVariableLength(new Boolean(true));

    $this->_parameter_list->assert($arguments);
  }

  /**
   * @covers \Typhoon\ParameterList::assert
   */
  public function testAssertFailureType()
  {
    $type = $this->getMockForAbstractClass(__NAMESPACE__.'\Type');
    $type
      ->expects($this->once())
      ->method('check')
      ->will($this->returnValue(false))
    ;
    $type
      ->expects($this->any())
      ->method('__toString')
      ->will($this->returnValue('foo'))
    ;
    $this->_parameter->setType($type);
    $this->_parameter_list[] = $this->_parameter;

    $this->setExpectedException(__NAMESPACE__.'\ParameterList\Exception\UnexpectedArgument', 'at index 0');
    $this->_parameter_list->assert(array(null));
  }

  /**
   * @covers \Typhoon\ParameterList::assert
   */
  public function testAssertFailureNoArguments()
  {
    $this->_parameter_list[] = $this->_parameter;

    $this->setExpectedException(__NAMESPACE__.'\ParameterList\Exception\MissingArgument', 'at index 0');
    $this->_parameter_list->assert(array());
  }

  /**
   * @covers \Typhoon\ParameterList::assert
   */
  public function testAssertFailureNotEnoughArguments()
  {
    $this->_parameter_list[] = $this->_parameter;
    $this->_parameter_list[] = $this->_parameter;

    $this->setExpectedException(__NAMESPACE__.'\ParameterList\Exception\MissingArgument', 'at index 1');
    $this->_parameter_list->assert(array(null));
  }

  /**
   * @covers \Typhoon\ParameterList::assert
   */
  public function testAssertFailureTooManyArguments()
  {
    $this->setExpectedException(__NAMESPACE__.'\ParameterList\Exception\UnexpectedArgument', 'at index 0');
    $this->_parameter_list->assert(array(null));
  }

  /**
   * @covers \Typhoon\ParameterList::assert
   */
  public function testAssertFailureTooManyArgumentsNonEmpty()
  {
    $this->_parameter_list[] = $this->_parameter;

    $this->setExpectedException(__NAMESPACE__.'\ParameterList\Exception\UnexpectedArgument', 'at index 1');
    $this->_parameter_list->assert(array(null, null));
  }

  /**
   * @covers \Typhoon\ParameterList::assert
   */
  public function testAssertFailureArgumentKey()
  {
    $this->_parameter_list[] = $this->_parameter;

    $this->setExpectedException(__NAMESPACE__.'\ParameterList\Exception\UnexpectedArgument', "expected 'integer'");
    $this->_parameter_list->assert(array('foo' => 'foo'));
  }

  /**
   * @covers \Typhoon\ParameterList::setVariableLength
   * @covers \Typhoon\ParameterList::variableLength
   */
  public function testVariableLength()
  {
    $this->assertFalse($this->_parameter_list->variableLength());

    $this->_parameter_list->setVariableLength(new Boolean(true));

    $this->assertTrue($this->_parameter_list->variableLength());
  }

  /**
   * @covers \Typhoon\ParameterList::offsetExists
   * @covers \Typhoon\ParameterList::offsetSet
   * @covers \Typhoon\ParameterList::offsetGet
   */
  public function testArrayAccess()
  {
    $this->assertInstanceOf('\ArrayAccess', $this->_parameter_list);

    $this->assertFalse(isset($this->_parameter_list[0]));

    $this->_parameter_list[] = $this->_parameter;

    $this->assertTrue(isset($this->_parameter_list[0]));
    $this->assertSame($this->_parameter, $this->_parameter_list[0]);

    $this->_parameter_list[] = $this->_parameter;

    $this->assertTrue(isset($this->_parameter_list[1]));
    $this->assertSame($this->_parameter, $this->_parameter_list[1]);
  }

  /**
   * @covers \Typhoon\ParameterList::offsetGet
   */
  public function testOffsetGetFailure()
  {
    $this->setExpectedException(__NAMESPACE__.'\ParameterList\Exception\UndefinedParameter');
    $this->_parameter_list[0];
  }

  /**
   * @covers \Typhoon\ParameterList::offsetUnset
   */
  public function testOffsetUnsetFailure()
  {
    $this->setExpectedException(__NAMESPACE__.'\Exception\NotImplemented');
    unset($this->_parameter_list[0]);
  }

  /**
   * @covers \Typhoon\ParameterList::offsetExists
   * @covers \Typhoon\ParameterList::offsetSet
   * @covers \Typhoon\ParameterList::offsetGet
   * @dataProvider unexpectedArgumentData
   */
  public function testUnexpectedArgumentFailure($method, array $arguments)
  {
    $this->setExpectedException(__NAMESPACE__.'\ParameterList\Exception\UnexpectedArgument');
    call_user_func_array(array($this->_parameter_list, $method), $arguments);
  }

  /**
   * @covers \Typhoon\ParameterList::offsetSet
   * @covers \Typhoon\ParameterList::getIterator
   */
  public function testOffsetSetAndGetIterator()
  {
    $this->assertInstanceOf('\Iterator', $this->_parameter_list->getIterator());

    $expected = array();
    $this->assertEquals($expected, iterator_to_array($this->_parameter_list->getIterator()));

    $this->_parameter_list[] = $this->_parameter;

    $expected[] = $this->_parameter;
    $this->assertEquals($expected, iterator_to_array($this->_parameter_list->getIterator()));
    
    $this->_parameter_list[] = $this->_parameter;

    $expected[] = $this->_parameter;
    $this->assertEquals($expected, iterator_to_array($this->_parameter_list->getIterator()));

    $iterations = 0;
    foreach ($this->_parameter_list->getIterator() as $parameter)
    {
      $this->assertSame($this->_parameter, $parameter);

      $iterations++;
    }

    $this->assertEquals(2, $iterations);
  }

  public function testTraversable()
  {
    $this->assertInstanceOf('\Traversable', $this->_parameter_list);
  }

  /**
   * @var ParameterList
   */
  protected $_parameter_list;

  /**
   * @var Parameter
   */
  protected $_parameter;
}