<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Parameter\ParameterList;

use Eloquent\Typhoon\Parameter\Parameter;
use Eloquent\Typhoon\Primitive\Boolean;

class ParameterListTest extends \Eloquent\Typhoon\Test\TestCase
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
   * @covers Eloquent\Typhoon\Parameter\ParameterList\ParameterList::setVariableLength
   * @covers Eloquent\Typhoon\Parameter\ParameterList\ParameterList::variableLength
   * @group parameter
   * @group collection
   * @group core
   */
  public function testVariableLength()
  {
    $this->assertFalse($this->_parameterList->variableLength());

    $this->_parameterList->setVariableLength(new Boolean(true));

    $this->assertTrue($this->_parameterList->variableLength());
  }

  /**
   * @covers Eloquent\Typhoon\Parameter\ParameterList\ParameterList::assertKeyExists
   * @group parameter
   * @group collection
   * @group core
   */
  public function testAssertKeyExists()
  {
    $this->_parameterList[] = $this->_parameter;
    $this->assertSame($this->_parameter, $this->_parameterList[0]);
  }

  /**
   * @covers Eloquent\Typhoon\Parameter\ParameterList\ParameterList::assertKeyExists
   * @group parameter
   * @group collection
   * @group core
   */
  public function testAssertKeyExistsFailure()
  {
    $this->setExpectedException(__NAMESPACE__.'\Exception\UndefinedParameterException');
    $this->_parameterList[0];
  }

  /**
   * @covers Eloquent\Typhoon\Parameter\ParameterList\ParameterList::remove
   * @group parameter
   * @group collection
   * @group core
   */
  public function testRemoveFailure()
  {
    $this->setExpectedException('Eloquent\Typhoon\Exception\NotImplementedException');
    $this->_parameterList->remove(0);
  }

  /**
   * @covers Eloquent\Typhoon\Parameter\ParameterList\ParameterList::keyType
   * @covers Eloquent\Typhoon\Parameter\ParameterList\ParameterList::keySetType
   * @covers Eloquent\Typhoon\Parameter\ParameterList\ParameterList::valueType
   * @dataProvider unexpectedTypeData
   * @group parameter
   * @group collection
   * @group core
   */
  public function testUnexpectedArgumentFailure($method, array $arguments)
  {
    $this->setExpectedException('Eloquent\Typhoon\Assertion\Exception\UnexpectedArgumentException');
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