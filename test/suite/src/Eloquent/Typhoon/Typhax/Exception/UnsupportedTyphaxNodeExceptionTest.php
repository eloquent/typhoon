<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Typhax\Exception;

use Eloquent\Typhoon\Primitive\String;
use Eloquent\Typhoon\Test\ExceptionTestCase;

class UnsupportedTyphaxNodeExceptionTest extends ExceptionTestCase
{
  /**
   * @return string
   */
  protected function exceptionClass()
  {
    return __NAMESPACE__.'\UnsupportedTyphaxNodeException';
  }

  /**
   * @return array
   */
  protected function defaultArguments()
  {
    return array($this->_class);
  }

  protected function setUp()
  {
    parent::setUp();

    $this->_class = new String('foo');
  }

  /**
   * @covers Eloquent\Typhoon\Typhax\Exception\UnsupportedTyphaxNodeException::__construct
   * @group exceptions
   * @group core
   */
  public function testConstructor()
  {
    $this->assertEquals("Unsupported Typhax node of class '".$this->_class."' encountered.", $this->exceptionFixture()->getMessage());

    parent::testConstructor();
  }

  /**
   * @var String
   */
  protected $_class;
}
