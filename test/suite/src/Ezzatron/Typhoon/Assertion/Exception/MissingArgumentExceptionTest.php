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

class MissingArgumentExceptionTest extends \Ezzatron\Typhoon\Test\ExceptionTestCase
{
  /**
   * @return string
   */
  protected function exceptionClass()
  {
    return __NAMESPACE__.'\MissingArgumentException';
  }

  /**
   * @return array
   */
  protected function defaultArguments()
  {
    return array($this->_index, $this->_expectedTypeName, $this->_parameterName);
  }

  protected function setUp()
  {
    parent::setUp();
    
    $this->_index = new Integer(0);
    $this->_expectedTypeName = new String('foo');
    $this->_parameterName = new String('bar');
  }

  /**
   * @covers Ezzatron\Typhoon\Assertion\Exception\MissingArgumentException
   * @group exceptions
   * @group assertion
   * @group core
   */
  public function testConstructor()
  {
    $exception = $this->exceptionFixture();
    $expected =
      "Missing argument at index "
      .$this->_index
      ." (".$this->_parameterName.")"
      ." - expected '"
      .$this->_expectedTypeName
      ."'."
    ;

    $this->assertEquals($expected, $exception->getMessage());
    $this->assertEquals(0, $exception->index());
    $this->assertEquals('foo', $exception->expectedTypeName());
    $this->assertEquals('bar', $exception->parameterName());

    $expected =
      "Missing argument at index "
      .$this->_index
      ." - expected '"
      .$this->_expectedTypeName
      ."'."
    ;

    $this->assertEquals($expected, $this->exceptionFixture(array($this->_index, $this->_expectedTypeName))->getMessage());

    parent::testConstructor();
  }

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