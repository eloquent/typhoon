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

class InvalidParameterTagExceptionTest extends ExceptionTestCase
{
  /**
   * @return string
   */
  protected function exceptionClass()
  {
    return __NAMESPACE__.'\InvalidParameterTagException';
  }

  /**
   * @return array
   */
  protected function defaultArguments()
  {
    return array($this->_tagContent);
  }

  protected function setUp()
  {
    parent::setUp();

    $this->_tagContent = new String('foo');
  }

  /**
   * @covers Eloquent\Typhoon\Typhax\Exception\InvalidParameterTagException::__construct
   * @group exceptions
   * @group core
   */
  public function testConstructor()
  {
    $this->assertEquals("Invalid documentation block parameter specification 'foo'.", $this->exceptionFixture()->getMessage());

    parent::testConstructor();
  }

  /**
   * @var String
   */
  protected $_tagContent;
}
