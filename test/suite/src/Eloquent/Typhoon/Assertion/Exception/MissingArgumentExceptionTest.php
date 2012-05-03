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
use Eloquent\Typhoon\Type\StringType;
use Phake;

class MissingArgumentExceptionTest extends \Eloquent\Typhoon\Test\ExceptionTestCase
{
  /**
   * @return string
   */
  protected function exceptionClass()
  {
    return __NAMESPACE__.'\MissingArgumentException';
  }

  /**
   * @return array
   */
  protected function defaultArguments()
  {
    return array($this->_index, $this->_expectedType, $this->_parameterName, null);
  }

  protected function setUp()
  {
    parent::setUp();

    $this->_index = new Integer(0);
    $this->_expectedType = new StringType;
    $this->_parameterName = new String('foo');
  }

  /**
   * @covers Eloquent\Typhoon\Assertion\Exception\MissingArgumentException
   * @covers Eloquent\Typhoon\Exception\UnexpectedInputException
   * @group exceptions
   * @group assertion
   * @group core
   */
  public function testConstructor()
  {
    $exception = $this->exceptionFixture();
    $expected = "Missing argument at index 0 (foo) - expected 'string'.";

    $this->assertSame($expected, $exception->getMessage());
    $this->assertSame(0, $exception->index());
    $this->assertSame($this->_expectedType, $exception->expectedType());
    $this->assertSame('foo', $exception->parameterName());
    $this->assertInstanceOf('Eloquent\Typhoon\Typhax\TyphaxTypeRenderer', $exception->typeRenderer());


    $expected = "Missing argument at index 0 - expected 'string'.";

    $this->assertEquals($expected, $this->exceptionFixture(array($this->_index, $this->_expectedType))->getMessage());


    $typeRenderer = Phake::mock('Eloquent\Typhoon\Type\Renderer\TypeRenderer');
    Phake::when($typeRenderer)->render($this->identicalTo($this->_expectedType))->thenReturn('bar');
    $exception = $this->exceptionFixture(array($this->_index, $this->_expectedType, $this->_parameterName, $typeRenderer));
    $expected = "Missing argument at index 0 (foo) - expected 'bar'.";

    $this->assertSame($expected, $exception->getMessage());
    $this->assertSame($typeRenderer, $exception->typeRenderer());
    Phake::verify($typeRenderer)->render($this->identicalTo($this->_expectedType));


    parent::testConstructor();
  }

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
