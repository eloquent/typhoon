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

use Ezzatron\Typhoon\Parameter;
use Ezzatron\Typhoon\Primitive\Integer;
use Ezzatron\Typhoon\Primitive\String;
use Ezzatron\Typhoon\Test\ExceptionTestCase;

class MissingArgumentTest extends ExceptionTestCase
{
  /**
   * @return string
   */
  protected function exceptionClass()
  {
    return __NAMESPACE__.'\MissingArgument';
  }

  /**
   * @return array
   */
  protected function defaultArguments()
  {
    return array($this->_index, $this->_parameter);
  }

  protected function setUp()
  {
    parent::setUp();
    
    $this->_index = new Integer(0);
    $this->_parameter = new Parameter;
    $this->_expectedTypeName = 'mixed';
  }

  /**
   * @covers Ezzatron\Typhoon\Assertion\Exception\MissingArgument::__construct
   */
  public function testConstructor()
  {
    $expected =
      "Missing argument at index "
      .$this->_index
      ." - expected '"
      .$this->_expectedTypeName
      ."'."
    ;

    $this->assertEquals($expected, $this->exceptionFixture()->getMessage());

    $parameterName = new String('foo');
    $expected =
      "Missing argument at index "
      .$this->_index
      ." (".$parameterName.")"
      ." - expected '"
      .$this->_expectedTypeName
      ."'."
    ;

    $parameter = new Parameter;
    $parameter->setName($parameterName);

    $this->assertEquals($expected, $this->exceptionFixture(array($this->_index, $parameter))->getMessage());

    parent::testConstructor();
  }

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