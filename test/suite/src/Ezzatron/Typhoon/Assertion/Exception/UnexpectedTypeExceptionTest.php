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
    return array($this->_typeName, $this->_expectedTypeName);
  }

  protected function setUp()
  {
    parent::setUp();
    
    $this->_typeName = new String('foo');
    $this->_expectedTypeName = new String('bar');
  }

  /**
   * @covers Ezzatron\Typhoon\Assertion\Exception\UnexpectedTypeException
   * @group exceptions
   * @group assertion
   * @group core
   */
  public function testConstructor()
  {
    $exception = $this->exceptionFixture();

    $expected =
      "Unexpected value of type '"
      .$this->_typeName
      ."' - expected '"
      .$this->_expectedTypeName
      ."'."
    ;

    $this->assertEquals($expected, $exception->getMessage());
    $this->assertEquals($this->_typeName->value(), $exception->typeName());
    $this->assertEquals($this->_expectedTypeName->value(), $exception->expectedTypeName());

    parent::testConstructor();
  }

  /**
   * @var String
   */
  protected $_typeName;

  /**
   * @var String
   */
  protected $_expectedTypeName;
}