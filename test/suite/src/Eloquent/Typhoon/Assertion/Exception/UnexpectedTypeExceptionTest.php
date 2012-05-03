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
    return array($this->_type, $this->_expectedType, null);
  }

  protected function setUp()
  {
    parent::setUp();

    $this->_type = new StringType;
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
    $this->assertSame($this->_type, $exception->type());
    $this->assertSame($this->_expectedType, $exception->expectedType());
    $this->assertInstanceOf('Eloquent\Typhoon\Typhax\TyphaxTypeRenderer', $exception->typeRenderer());


    $typeRenderer = Phake::mock('Eloquent\Typhoon\Type\Renderer\TypeRenderer');
    Phake::when($typeRenderer)->render($this->identicalTo($this->_type))->thenReturn('foo');
    Phake::when($typeRenderer)->render($this->identicalTo($this->_expectedType))->thenReturn('bar');
    $exception = $this->exceptionFixture(array($this->_type, $this->_expectedType, $typeRenderer));
    $expected =
      "Unexpected value of type 'foo' - expected 'bar'."
    ;

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
   * @var Type
   */
  protected $_expectedType;
}
