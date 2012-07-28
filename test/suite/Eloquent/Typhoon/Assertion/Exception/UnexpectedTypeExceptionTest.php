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

use Eloquent\Typhoon\Type\IntegerType;
use Eloquent\Typhoon\Type\StringType;
use Phake;

class UnexpectedTypeExceptionTest extends \Eloquent\Typhoon\Test\ExceptionTestCase
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
    return array($this->_value, $this->_expectedType, null, null);
  }

  protected function setUp()
  {
    parent::setUp();

    $this->_value = 'foo';
    $this->_expectedType = new IntegerType;
  }

  /**
   * @covers Eloquent\Typhoon\Assertion\Exception\UnexpectedTypeException
   * @covers Eloquent\Typhoon\Exception\UnexpectedInputException
   * @group exceptions
   * @group assertion
   * @group core
   */
  public function testConstructor()
  {
    $exception = $this->exceptionFixture();
    $expected =
      "Unexpected value of type 'string' - expected 'integer'."
    ;

    $this->assertSame($expected, $exception->getMessage());
    $this->assertSame($this->_value, $exception->value());
    $this->assertSame($this->_expectedType, $exception->expectedType());
    $this->assertInstanceOf('Eloquent\Typhoon\Typhax\TyphaxTypeRenderer', $exception->typeRenderer());


    $typeInspector = Phake::mock('Eloquent\Typhoon\Type\Inspector\TypeInspector');
    $typeRenderer = Phake::mock('Eloquent\Typhoon\Type\Renderer\TypeRenderer');
    $type = Phake::mock('Eloquent\Typhoon\Type\Type');
    Phake::when($typeInspector)->typeOf($this->_value)->thenReturn($type);
    Phake::when($typeRenderer)->render($this->identicalTo($type))->thenReturn('bar');
    Phake::when($typeRenderer)->render($this->identicalTo($this->_expectedType))->thenReturn('baz');
    $exception = $this->exceptionFixture(array($this->_value, $this->_expectedType, $typeInspector, $typeRenderer));
    $expected =
      "Unexpected value of type 'bar' - expected 'baz'."
    ;

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
   * @var Type
   */
  protected $_expectedType;
}
