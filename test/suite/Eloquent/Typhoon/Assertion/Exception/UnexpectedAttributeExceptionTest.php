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
    return array($this->_value, $this->_attributeName, $this->_expectedType, $this->_holderName, null, null);
  }

  protected function setUp()
  {
    parent::setUp();

    $this->_value = 'foo';
    $this->_attributeName = new String('bar');
    $this->_expectedType = new IntegerType;
    $this->_holderName = new String('baz');
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
    $expected = "Unexpected value of type 'string' for attribute 'bar' of 'baz' - expected 'integer'.";

    $this->assertSame($expected, $exception->getMessage());
    $this->assertSame($this->_value, $exception->value());
    $this->assertSame($this->_attributeName->value(), $exception->attributeName());
    $this->assertSame($this->_expectedType, $exception->expectedType());
    $this->assertSame($this->_holderName->value(), $exception->holderName());
    $this->assertInstanceOf('Eloquent\Typhoon\Typhax\TyphaxTypeRenderer', $exception->typeRenderer());


    $expected = "Unexpected value of type 'string' for attribute 'bar' - expected 'integer'.";

    $this->assertSame($expected, $this->exceptionFixture(array($this->_value, $this->_attributeName, $this->_expectedType))->getMessage());


    $expected = "Unexpected value of type 'string' for attribute 'bar'.";

    $this->assertSame($expected, $this->exceptionFixture(array($this->_value, $this->_attributeName))->getMessage());


    $typeInspector = Phake::mock('Eloquent\Typhoon\Type\Inspector\TypeInspector');
    $typeRenderer = Phake::mock('Eloquent\Typhoon\Type\Renderer\TypeRenderer');
    $type = Phake::mock('Eloquent\Typhoon\Type\Type');
    Phake::when($typeInspector)->typeOf($this->_value)->thenReturn($type);
    Phake::when($typeRenderer)->render($this->identicalTo($type))->thenReturn('qux');
    Phake::when($typeRenderer)->render($this->identicalTo($this->_expectedType))->thenReturn('doom');
    $exception = $this->exceptionFixture(array($this->_value, $this->_attributeName, $this->_expectedType, $this->_holderName, $typeInspector, $typeRenderer));
    $expected = "Unexpected value of type 'qux' for attribute 'bar' of 'baz' - expected 'doom'.";

    $this->assertSame($expected, $exception->getMessage());
    $this->assertSame($typeInspector, $exception->typeInspector());
    $this->assertSame($typeRenderer, $exception->typeRenderer());
    Phake::verify($typeInspector)->typeOf($this->_value);
    Phake::verify($typeRenderer)->render($this->identicalTo($type));
    Phake::verify($typeRenderer)->render($this->identicalTo($this->_expectedType));


    parent::testConstructor();
  }

  /**
   * @var mixed
   */
  protected $_value;

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
