<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Exception;

use Eloquent\Typhoon\Primitive\String;
use Eloquent\Typhoon\Test\ExceptionTestCase;

class UndefinedMethodExceptionTest extends ExceptionTestCase
{
  /**
   * @return string
   */
  protected function exceptionClass()
  {
    return __NAMESPACE__.'\UndefinedMethodException';
  }

  /**
   * @return array
   */
  protected function defaultArguments()
  {
    return array($this->_class, $this->_method);
  }

  protected function setUp()
  {
    parent::setUp();

    $this->_class = new String('foo');
    $this->_method = new String('bar');
  }

  /**
   * @covers Eloquent\Typhoon\Exception\UndefinedMethodException::__construct
   * @group exceptions
   * @group core
   */
  public function testConstructor()
  {
    $this->assertEquals('Call to undefined method '.$this->_class.'::'.$this->_method.'().', $this->exceptionFixture()->getMessage());

    parent::testConstructor();
  }

  /**
   * @var String
   */
  protected $_class;

  /**
   * @var String
   */
  protected $_method;
}
