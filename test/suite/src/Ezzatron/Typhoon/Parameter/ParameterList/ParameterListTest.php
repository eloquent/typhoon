<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ezzatron\Typhoon\Parameter\ParameterList;

use Ezzatron\Typhoon\Parameter\Parameter;
use Ezzatron\Typhoon\Primitive\Boolean;

class ParameterListTest extends \Ezzatron\Typhoon\Test\TestCase
{
  /**
   * @return array
   */
  public function unexpectedTypeData()
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
   * @covers Ezzatron\Typhoon\Parameter\ParameterList\ParameterList::setVariableLength
   * @covers Ezzatron\Typhoon\Parameter\ParameterList\ParameterList::variableLength
   */
  public function testVariableLength()
  {
    $this->assertFalse($this->_parameterList->variableLength());

    $this->_parameterList->setVariableLength(new Boolean(true));

    $this->assertTrue($this->_parameterList->variableLength());
  }

  /**
   * @covers Ezzatron\Typhoon\Parameter\ParameterList\ParameterList::assertKeyExists
   */
  public function testAssertKeyExists()
  {
    $this->_parameterList[] = $this->_parameter;
    $this->assertSame($this->_parameter, $this->_parameterList[0]);
  }

  /**
   * @covers Ezzatron\Typhoon\Parameter\ParameterList\ParameterList::assertKeyExists
   */
  public function testAssertKeyExistsFailure()
  {
    $this->setExpectedException(__NAMESPACE__.'\Exception\UndefinedParameterException');
    $this->_parameterList[0];
  }

  /**
   * @covers Ezzatron\Typhoon\Parameter\ParameterList\ParameterList::remove
   */
  public function testRemoveFailure()
  {
    $this->setExpectedException('Ezzatron\Typhoon\Exception\NotImplementedException');
    $this->_parameterList->remove(0);
  }

  /**
   * @covers Ezzatron\Typhoon\Parameter\ParameterList\ParameterList::keyType
   * @covers Ezzatron\Typhoon\Parameter\ParameterList\ParameterList::keySetType
   * @covers Ezzatron\Typhoon\Parameter\ParameterList\ParameterList::valueType
   * @dataProvider unexpectedTypeData
   */
  public function testUnexpectedArgumentFailure($method, array $arguments)
  {
    $this->setExpectedException('Ezzatron\Typhoon\Assertion\Exception\UnexpectedArgumentException');
    call_user_func_array(array($this->_parameterList, $method), $arguments);
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