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
    return array($this->_typeName, $this->_index, $this->_expectedTypeName, $this->_parameterName);
  }

  protected function setUp()
  {
    parent::setUp();
    
    $this->_typeName = new String('foo');
    $this->_index = new Integer(0);
    $this->_expectedTypeName = new String('bar');
    $this->_parameterName = new String('baz');
  }

  /**
   * @covers Ezzatron\Typhoon\Assertion\Exception\UnexpectedArgumentException
   * @group exceptions
   * @group assertion
   * @group core
   */
  public function testConstructor()
  {
    $exception = $this->exceptionFixture();
    $expected =
      "Unexpected argument of type '"
      .$this->_typeName
      ."' at index "
      .$this->_index
      ." ("
      .$this->_parameterName
      .")"
      ." - expected '"
      .$this->_expectedTypeName
      ."'."
    ;

    $this->assertEquals($expected, $exception->getMessage());
    $this->assertEquals($this->_typeName->value(), $exception->typeName());
    $this->assertEquals($this->_index->value(), $exception->index());
    $this->assertEquals($this->_expectedTypeName->value(), $exception->expectedTypeName());
    $this->assertEquals($this->_parameterName->value(), $exception->parameterName());

    $expected =
      "Unexpected argument of type '"
      .$this->_typeName
      ."' at index "
      .$this->_index
      ." - expected '"
      .$this->_expectedTypeName
      ."'."
    ;

    $this->assertEquals($expected, $this->exceptionFixture(array($this->_typeName, $this->_index, $this->_expectedTypeName))->getMessage());

    $expected =
      "Unexpected argument of type '"
      .$this->_typeName
      ."' at index "
      .$this->_index
      ."."
    ;

    $this->assertEquals($expected, $this->exceptionFixture(array($this->_typeName, $this->_index))->getMessage());

    parent::testConstructor();
  }

  /**
   * @var String
   */
  protected $_typeName;

  /**
   * @var Integer
   */
  protected $_index;

  /**
   * @var String
   */
  protected $_expectedTypeName;

  /**
   * @var String
   */
  protected $_parameterName;
}