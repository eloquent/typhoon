<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Assertion\Exception;

use Eloquent\Typhoon\Primitive\String;

class UnsupportedAttributeExceptionTest extends \Eloquent\Typhoon\Test\ExceptionTestCase
{
  /**
   * @return string
   */
  protected function exceptionClass()
  {
    return __NAMESPACE__.'\UnsupportedAttributeException';
  }

  /**
   * @return array
   */
  protected function defaultArguments()
  {
    return array($this->_attributeName, $this->_holderName);
  }

  protected function setUp()
  {
    parent::setUp();

    $this->_attributeName = new String('bar');
    $this->_holderName = new String('qux');
  }

  /**
   * @covers Eloquent\Typhoon\Assertion\Exception\UnsupportedAttributeException
   * @covers Eloquent\Typhoon\Exception\UndefinedKeyException
   * @covers Eloquent\Typhoon\Exception\UnexpectedInputException
   * @group exceptions
   * @group assertion
   * @group core
   */
  public function testConstructor()
  {
    $exception = $this->exceptionFixture();
    $expected =
      "Attribute '"
      .$this->_attributeName
      ."' is not supported by '"
      .$this->_holderName
      ."'."
    ;

    $this->assertEquals($expected, $exception->getMessage());
    $this->assertEquals($this->_attributeName->value(), $exception->attributeName());
    $this->assertEquals($this->_holderName->value(), $exception->holderName());

    $expected =
      "Attribute '"
      .$this->_attributeName
      ."' is not supported."
    ;

    $this->assertEquals($expected, $this->exceptionFixture(array($this->_attributeName))->getMessage());

    parent::testConstructor();
  }

  /**
   * @var String
   */
  protected $_attributeName;

  /**
   * @var String
   */
  protected $_holderName;
}
