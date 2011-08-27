<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Typhoon\Collection\Exception;

use Typhoon\Primitive\String;
use Typhoon\Test\ExceptionTestCase;

class UndefinedKeyTest extends ExceptionTestCase
{
  /**
   * @return string
   */
  protected function exceptionClass()
  {
    return __NAMESPACE__.'\UndefinedKey';
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
   * @covers Typhoon\Collection\Exception\UndefinedKey::__construct
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