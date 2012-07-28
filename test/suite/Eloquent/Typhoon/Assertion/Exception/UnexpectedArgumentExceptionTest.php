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

use Eloquent\Typhoon\Primitive\Integer;
use Eloquent\Typhoon\Primitive\String;
use Eloquent\Typhoon\Type\IntegerType;
use Eloquent\Typhoon\Type\StringType;
use Phake;

class UnexpectedArgumentExceptionTest extends \Eloquent\Typhoon\Test\ExceptionTestCase
{
  /**
   * @return string
   */
  protected function exceptionClass()
  {
    return __NAMESPACE__.'\UnexpectedArgumentException';
  }

  /**
   * @return array
   */
  protected function defaultArguments()
  {
    return array($this->_value, $this->_index, $this->_expectedType, $this->_parameterName, null, null);
  }

  protected function setUp()
  {
    parent::setUp();

    $this->_value = 'foo';
    $this->_index = new Integer(0);
    $this->_expectedType = new IntegerType;
    $this->_parameterName = new String('bar');
  }

  /**
   * @covers Eloquent\Typhoon\Assertion\Exception\UnexpectedArgumentException
   * @covers Eloquent\Typhoon\Exception\UnexpectedInputException
   * @group exceptions
   * @group assertion
   * @group core
   */
  public function testConstructor()
  {
    $exception = $this->exceptionFixture();
    $expected = "Unexpected argument of type 'string' at index 0 (bar) - expected 'integer'.";

    $this->assertSame($expected, $exception->getMessage());
    $this->assertSame($this->_value, $exception->value());
    $this->assertSame($this->_index->value(), $exception->index());
    $this->assertSame($this->_expectedType, $exception->expectedType());
    $this->assertSame($this->_parameterName->value(), $exception->parameterName());
    $this->assertInstanceOf('Eloquent\Typhoon\Typhax\TyphaxTypeRenderer', $exception->typeRenderer());


    $expected = "Unexpected argument of type 'string' at index 0 - expected 'integer'.";

    $this->assertSame($expected, $this->exceptionFixture(array($this->_value, $this->_index, $this->_expectedType))->getMessage());


    $expected = "Unexpected argument of type 'string' at index 0.";

    $this->assertSame($expected, $this->exceptionFixture(array($this->_value, $this->_index))->getMessage());


    $typeInspector = Phake::mock('Eloquent\Typhoon\Type\Inspector\TypeInspector');
    $typeRenderer = Phake::mock('Eloquent\Typhoon\Type\Renderer\TypeRenderer');
    $type = Phake::mock('Eloquent\Typhoon\Type\Type');
    Phake::when($typeInspector)->typeOf($this->_value)->thenReturn($type);
    Phake::when($typeRenderer)->render($this->identicalTo($type))->thenReturn('baz');
    Phake::when($typeRenderer)->render($this->identicalTo($this->_expectedType))->thenReturn('qux');
    $exception = $this->exceptionFixture(array($this->_value, $this->_index, $this->_expectedType, $this->_parameterName, $typeInspector, $typeRenderer));
    $expected = "Unexpected argument of type 'baz' at index 0 (bar) - expected 'qux'.";

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
   * @var Integer
   */
  protected $_index;

  /**
   * @var Type
   */
  protected $_expectedType;

  /**
   * @var String
   */
  protected $_parameterName;
}
