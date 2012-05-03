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
    return array($this->_type, $this->_index, $this->_expectedType, $this->_parameterName, null);
  }

  protected function setUp()
  {
    parent::setUp();

    $this->_type = new StringType;
    $this->_index = new Integer(0);
    $this->_expectedType = new IntegerType;
    $this->_parameterName = new String('foo');
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
    $expected = "Unexpected argument of type 'string' at index 0 (foo) - expected 'integer'.";

    $this->assertSame($expected, $exception->getMessage());
    $this->assertSame($this->_type, $exception->type());
    $this->assertSame($this->_index->value(), $exception->index());
    $this->assertSame($this->_expectedType, $exception->expectedType());
    $this->assertSame($this->_parameterName->value(), $exception->parameterName());
    $this->assertInstanceOf('Eloquent\Typhoon\Typhax\TyphaxTypeRenderer', $exception->typeRenderer());


    $expected = "Unexpected argument of type 'string' at index 0 - expected 'integer'.";

    $this->assertSame($expected, $this->exceptionFixture(array($this->_type, $this->_index, $this->_expectedType))->getMessage());


    $expected = "Unexpected argument of type 'string' at index 0.";

    $this->assertSame($expected, $this->exceptionFixture(array($this->_type, $this->_index))->getMessage());


    $typeRenderer = Phake::mock('Eloquent\Typhoon\Type\Renderer\TypeRenderer');
    Phake::when($typeRenderer)->render($this->identicalTo($this->_type))->thenReturn('bar');
    Phake::when($typeRenderer)->render($this->identicalTo($this->_expectedType))->thenReturn('baz');
    $exception = $this->exceptionFixture(array($this->_type, $this->_index, $this->_expectedType, $this->_parameterName, $typeRenderer));
    $expected = "Unexpected argument of type 'bar' at index 0 (foo) - expected 'baz'.";

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
