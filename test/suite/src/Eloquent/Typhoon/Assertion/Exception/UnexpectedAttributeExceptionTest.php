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
use Eloquent\Typhoon\Type\IntegerType;
use Eloquent\Typhoon\Type\StringType;
use Phake;

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
    return array($this->_type, $this->_attributeName, $this->_expectedType, $this->_holderName, null);
  }

  protected function setUp()
  {
    parent::setUp();

    $this->_type = new StringType;
    $this->_attributeName = new String('foo');
    $this->_expectedType = new IntegerType;
    $this->_holderName = new String('bar');
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
    $expected = "Unexpected value of type 'string' for attribute 'foo' of 'bar' - expected 'integer'.";

    $this->assertSame($expected, $exception->getMessage());
    $this->assertSame($this->_type, $exception->type());
    $this->assertSame($this->_attributeName->value(), $exception->attributeName());
    $this->assertSame($this->_expectedType, $exception->expectedType());
    $this->assertSame($this->_holderName->value(), $exception->holderName());
    $this->assertInstanceOf('Eloquent\Typhoon\Typhax\TyphaxTypeRenderer', $exception->typeRenderer());


    $expected = "Unexpected value of type 'string' for attribute 'foo' - expected 'integer'.";

    $this->assertSame($expected, $this->exceptionFixture(array($this->_type, $this->_attributeName, $this->_expectedType))->getMessage());


    $expected = "Unexpected value of type 'string' for attribute 'foo'.";

    $this->assertSame($expected, $this->exceptionFixture(array($this->_type, $this->_attributeName))->getMessage());


    $typeRenderer = Phake::mock('Eloquent\Typhoon\Type\Renderer\TypeRenderer');
    Phake::when($typeRenderer)->render($this->identicalTo($this->_type))->thenReturn('baz');
    Phake::when($typeRenderer)->render($this->identicalTo($this->_expectedType))->thenReturn('qux');
    $exception = $this->exceptionFixture(array($this->_type, $this->_attributeName, $this->_expectedType, $this->_holderName, $typeRenderer));
    $expected = "Unexpected value of type 'baz' for attribute 'foo' of 'bar' - expected 'qux'.";

    $this->assertSame($expected, $exception->getMessage());
    $this->assertSame($typeRenderer, $exception->typeRenderer());
    Phake::verify($typeRenderer)->render($this->identicalTo($this->_type));
    Phake::verify($typeRenderer)->render($this->identicalTo($this->_expectedType));


    parent::testConstructor();
  }

  /**
   * @var Type
   */
  protected $_type;

  /**
   * @var String
   */
  protected $_attributeName;

  /**
   * @var Type
   */
  protected $_expectedType;

  /**
   * @var String
   */
  protected $_holderName;
}
