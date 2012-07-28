<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Parameter\ParameterList\Exception;

use Eloquent\Typhoon\Primitive\String;
use Eloquent\Typhoon\Test\ExceptionTestCase;

class VariableLengthParameterNotLastExceptionTest extends ExceptionTestCase
{
  /**
   * @return string
   */
  protected function exceptionClass()
  {
    return __NAMESPACE__.'\VariableLengthParameterNotLastException';
  }

  /**
   * @return array
   */
  protected function defaultArguments()
  {
    return array($this->_parameterName);
  }

  protected function setUp()
  {
    parent::setUp();

    $this->_parameterName = new String('foo');
  }

  /**
   * @covers Eloquent\Typhoon\Parameter\ParameterList\Exception\VariableLengthParameterNotLastException::__construct
   * @group exceptions
   * @group core
   */
  public function testConstructor()
  {
    $this->assertEquals("Parameter 'foo' is marked as variable length, but is not the last parameter.", $this->exceptionFixture()->getMessage());

    parent::testConstructor();
  }

  /**
   * @var String
   */
  protected $_parameterName;
}
