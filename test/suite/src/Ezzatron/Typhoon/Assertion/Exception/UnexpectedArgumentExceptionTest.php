<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ezzatron\Typhoon\Assertion\Exception;

use Ezzatron\Typhoon\Parameter\Parameter;
use Ezzatron\Typhoon\Primitive\Integer;
use Ezzatron\Typhoon\Primitive\String;

class UnexpectedArgumentExceptionTest extends \Ezzatron\Typhoon\Test\ExceptionTestCase
{
  /**
   * @return string
   */
  protected function exceptionClass()
  {
    return __NAMESPACE__.'\UnexpectedArgumentException';
  }

  /**
   * @return array
   */
  protected function defaultArguments()
  {
    return array($this->_value, $this->_index, $this->_parameter);
  }

  protected function setUp()
  {
    parent::setUp();
    
    $this->_value = 'foo';
    $this->_index = new Integer(0);
    $this->_parameter = new Parameter;
    $this->_expectedTypeName = 'mixed';
  }

  /**
   * @covers Ezzatron\Typhoon\Assertion\Exception\UnexpectedArgumentException::__construct
   */
  public function testConstructor()
  {
    $expected =
      "Unexpected argument at index "
      .$this->_index
      ." - expected '"
      .$this->_expectedTypeName
      ."'."
    ;

    $this->assertEquals($expected, $this->exceptionFixture()->getMessage());

    $parameterName = new String('foo');
    $expected =
      "Unexpected argument at index "
      .$this->_index
      ." (".$parameterName.")"
      ." - expected '"
      .$this->_expectedTypeName
      ."'."
    ;

    $parameter = new Parameter;
    $parameter->setName($parameterName);

    $this->assertEquals($expected, $this->exceptionFixture(array($this->_value, $this->_index, $parameter))->getMessage());

    $expected =
      "Unexpected argument at index "
      .$this->_index
      ."."
    ;

    $this->assertEquals($expected, $this->exceptionFixture(array($this->_value, $this->_index))->getMessage());

    parent::testConstructor();
  }

  /**
   * @var mixed
   */
  protected $_value;

  /**
   * @var Integer
   */
  protected $_index;

  /**
   * @var Parameter
   */
  protected $_parameter;

  /**
   * @var string
   */
  protected $_expectedTypeName;
}