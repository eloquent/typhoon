<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ezzatron\Typhoon\Attribute\Exception;

use Ezzatron\Typhoon\Primitive\String;

class FinalizedExceptionTest extends \Ezzatron\Typhoon\Test\ExceptionTestCase
{
  /**
   * @return string
   */
  protected function exceptionClass()
  {
    return __NAMESPACE__.'\FinalizedException';
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
   * @covers Ezzatron\Typhoon\Attribute\Exception\FinalizedException::__construct
   * @group exceptions
   * @group attribute
   * @group core
   */
  public function testConstructor()
  {
    $this->assertEquals("Unable to modify key '".$this->_key."'. Attributes have been finalized.", $this->exceptionFixture()->getMessage());

    parent::testConstructor();
  }

  /**
   * @var String
   */
  protected $_key;
}

