<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Collection\Exception;

use Eloquent\Typhoon\Primitive\String;

class UndefinedKeyExceptionTest extends \Eloquent\Typhoon\Test\ExceptionTestCase
{
  /**
   * @return string
   */
  protected function exceptionClass()
  {
    return __NAMESPACE__.'\UndefinedKeyException';
  }

  /**
   * @return array
   */
  protected function defaultArguments()
  {
    return array($this->_key);
  }

  protected function setUp()
  {
    parent::setUp();

    $this->_key = new String('foo');
  }

  /**
   * @covers Eloquent\Typhoon\Collection\Exception\UndefinedKeyException::__construct
   * @group exceptions
   * @group collection
   * @group core
   */
  public function testConstructor()
  {
    $this->assertEquals("Undefined key '".$this->_key."'.", $this->exceptionFixture()->getMessage());

    parent::testConstructor();
  }

  /**
   * @var String
   */
  protected $_key;
}
