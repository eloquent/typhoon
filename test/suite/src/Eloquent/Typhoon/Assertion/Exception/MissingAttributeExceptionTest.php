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

class MissingAttributeExceptionTest extends \Eloquent\Typhoon\Test\ExceptionTestCase
{
  /**
   * @return string
   */
  protected function exceptionClass()
  {
    return __NAMESPACE__.'\MissingAttributeException';
  }

  /**
   * @return array
   */
  protected function defaultArguments()
  {
    return array($this->_attributeName, $this->_expectedTypeName, $this->_holderName);
  }

  protected function setUp()
  {
    parent::setUp();

    $this->_attributeName = new String('foo');
    $this->_expectedTypeName = new String('bar');
    $this->_holderName = new String('baz');
  }

  /**
   * @covers Eloquent\Typhoon\Assertion\Exception\MissingAttributeException
   * @covers Eloquent\Typhoon\Exception\UnexpectedInputException
   * @group exceptions
   * @group assertion
   * @group core
   */
  public function testConstructor()
  {
    $exception = $this->exceptionFixture();
    $expected =
      "Missing required attribute '"
      .$this->_attributeName
      ."' for '"
      .$this->_holderName
      ."' - expected '"
      .$this->_expectedTypeName
      ."'."
    ;

    $this->assertEquals($expected, $exception->getMessage());
    $this->assertEquals('foo', $exception->attributeName());
    $this->assertEquals('bar', $exception->expectedTypeName());
    $this->assertEquals('baz', $exception->holderName());

    $expected =
      "Missing required attribute '"
      .$this->_attributeName
      ."' - expected '"
      .$this->_expectedTypeName
      ."'."
    ;

    $this->assertEquals($expected, $this->exceptionFixture(array($this->_attributeName, $this->_expectedTypeName))->getMessage());

    parent::testConstructor();
  }

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
