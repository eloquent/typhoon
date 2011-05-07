<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Typhoon\Type\Exception;

use Typhoon;
use Typhoon\Primitive\String;
use Typhoon\Test\ExceptionTestCase;

class UnexpectedTypeTest extends ExceptionTestCase
{
  /**
   * @return string
   */
  protected function exceptionClass()
  {
    return __NAMESPACE__.'\UnexpectedType';
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
    $this->_value = 'foo';
    $this->_expectedTypeName = new String('foo');
  }

  /**
   * @covers Typhoon\Type\Exception\UnexpectedType::__construct
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