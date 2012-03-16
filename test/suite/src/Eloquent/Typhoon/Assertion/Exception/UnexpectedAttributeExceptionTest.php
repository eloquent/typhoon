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

class UnexpectedAttributeExceptionTest extends \Eloquent\Typhoon\Test\ExceptionTestCase
{
  /**
   * @return string
   */
  protected function exceptionClass()
  {
    return __NAMESPACE__.'\UnexpectedAttributeException';
  }

  /**
   * @return array
   */
  protected function defaultArguments()
  {
    return array($this->_typeName, $this->_attributeName, $this->_expectedTypeName, $this->_holderName);
  }

  protected function setUp()
  {
    parent::setUp();

    $this->_typeName = new String('foo');
    $this->_attributeName = new String('bar');
    $this->_expectedTypeName = new String('baz');
    $this->_holderName = new String('qux');
  }

  /**
   * @covers Eloquent\Typhoon\Assertion\Exception\UnexpectedAttributeException
   * @covers Eloquent\Typhoon\Exception\UnexpectedInputException
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
      ."' for attribute '"
      .$this->_attributeName
      ."' of '"
      .$this->_holderName
      ."' - expected '"
      .$this->_expectedTypeName
      ."'."
    ;

    $this->assertEquals($expected, $exception->getMessage());
    $this->assertEquals($this->_typeName->value(), $exception->typeName());
    $this->assertEquals($this->_attributeName->value(), $exception->attributeName());
    $this->assertEquals($this->_expectedTypeName->value(), $exception->expectedTypeName());
    $this->assertEquals($this->_holderName->value(), $exception->holderName());

    $expected =
      "Unexpected value of type '"
      .$this->_typeName
      ."' for attribute '"
      .$this->_attributeName
      ."' - expected '"
      .$this->_expectedTypeName
      ."'."
    ;

    $this->assertEquals($expected, $this->exceptionFixture(array($this->_typeName, $this->_attributeName, $this->_expectedTypeName))->getMessage());

    $expected =
      "Unexpected value of type '"
      .$this->_typeName
      ."' for attribute '"
      .$this->_attributeName
      ."'."
    ;

    $this->assertEquals($expected, $this->exceptionFixture(array($this->_typeName, $this->_attributeName))->getMessage());

    parent::testConstructor();
  }

  /**
   * @var String
   */
  protected $_typeName;

  /**
   * @var String
   */
  protected $_attributeName;

  /**
   * @var String
   */
  protected $_expectedTypeName;

  /**
   * @var String
   */
  protected $_holderName;
}
