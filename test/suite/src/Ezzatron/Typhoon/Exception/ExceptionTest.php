<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ezzatron\Typhoon\Exception;

use Ezzatron\Typhoon\Primitive\String;
use Ezzatron\Typhoon\Test\ExceptionTestCase;

class ExceptionTest extends ExceptionTestCase
{
  /**
   * @return string
   */
  protected function exceptionClass()
  {
    return __NAMESPACE__.'\Exception';
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
   * @covers Ezzatron\Typhoon\Exception\Exception::__construct
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