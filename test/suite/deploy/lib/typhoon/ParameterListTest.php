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

use Typhoon\Primitive\Boolean;
use Typhoon\Test\TestCase;

class ParameterListTest extends TestCase
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
    parent::setUp();
    
    $this->_parameterList = new ParameterList;
    $this->_parameter = new Parameter;
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
    $this->setExpectedException(__NAMESPACE__.'\Assertion\Exception\UnexpectedArgument');
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

  /**
   * @covers Typhoon\ParameterList
   */
  public function testTraversable()
  {
    $this->assertInstanceOf('Traversable', $this->_parameterList);
  }

  /**
   * @covers Typhoon\ParameterList::count
   */
  public function testCount()
  {
    $this->assertEquals(0, count($this->_parameterList));

    $this->_parameterList[] = $this->_parameter;

    $this->assertEquals(1, count($this->_parameterList));

    $this->_parameterList[] = $this->_parameter;

    $this->assertEquals(2, count($this->_parameterList));
  }

  /**
   * @covers Typhoon\ParameterList
   */
  public function testCountable()
  {
    $this->assertInstanceOf('Countable', $this->_parameterList);
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