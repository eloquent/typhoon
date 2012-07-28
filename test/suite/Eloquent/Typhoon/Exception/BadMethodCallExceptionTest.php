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

class BadMethodCallExceptionTest extends \Eloquent\Typhoon\Test\ExceptionTestCase
{
  /**
   * @return string
   */
  protected function exceptionClass()
  {
    return __NAMESPACE__.'\BadMethodCallException';
  }

  /**
   * @return array
   */
  protected function defaultArguments()
  {
    return array($this->_message);
  }

  protected function setUp()
  {
    parent::setUp();

    $this->_message = new String('foo');
  }

  /**
   * @covers Eloquent\Typhoon\Exception\BadMethodCallException::__construct
   * @covers Eloquent\Typhoon\Exception\Exception
   * @group exceptions
   * @group core
   */
  public function testConstructor()
  {
    $this->assertEquals((string)$this->_message, $this->exceptionFixture()->getMessage());

    parent::testConstructor();
  }

  /**
   * @var String
   */
  protected $_message;
}
