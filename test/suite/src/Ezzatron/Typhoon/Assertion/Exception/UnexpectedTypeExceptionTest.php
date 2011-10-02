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

use Ezzatron\Typhoon\Primitive\String;

class UnexpectedTypeExceptionTest extends \Ezzatron\Typhoon\Test\ExceptionTestCase
{
  /**
   * @return string
   */
  protected function exceptionClass()
  {
    return __NAMESPACE__.'\UnexpectedTypeException';
  }

  /**
   * @return array
   */
  protected function defaultArguments()
  {
    return array($this->_value, $this->_expectedTypeName);
  }

  protected function setUp()
  {
    parent::setUp();
    
    $this->_value = 'foo';
    $this->_expectedTypeName = new String('foo');
  }

  /**
   * @covers Ezzatron\Typhoon\Assertion\Exception\UnexpectedTypeException::__construct
   */
  public function testConstructor()
  {
    $this->assertEquals("Unexpected type - expected '".$this->_expectedTypeName."'.", $this->exceptionFixture()->getMessage());

    parent::testConstructor();
  }

  /**
   * @var mixed
   */
  protected $_value;

  /**
   * @var string
   */
  protected $_expectedTypeName;
}