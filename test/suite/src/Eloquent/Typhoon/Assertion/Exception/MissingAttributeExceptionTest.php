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
use Eloquent\Typhoon\Type\StringType;
use Phake;

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
    return array($this->_attributeName, $this->_expectedType, $this->_holderName, null);
  }

  protected function setUp()
  {
    parent::setUp();

    $this->_attributeName = new String('foo');
    $this->_expectedType = new StringType;
    $this->_holderName = new String('bar');
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
    $expected = "Missing required attribute 'foo' for 'bar' - expected 'string'.";

    $this->assertSame($expected, $exception->getMessage());
    $this->assertSame('foo', $exception->attributeName());
    $this->assertSame($this->_expectedType, $exception->expectedType());
    $this->assertSame('bar', $exception->holderName());
    $this->assertInstanceOf('Eloquent\Typhoon\Typhax\TyphaxTypeRenderer', $exception->typeRenderer());


    $expected = "Missing required attribute 'foo' - expected 'string'.";

    $this->assertSame($expected, $this->exceptionFixture(array($this->_attributeName, $this->_expectedType))->getMessage());


    $typeRenderer = Phake::mock('Eloquent\Typhoon\Type\Renderer\TypeRenderer');
    Phake::when($typeRenderer)->render($this->identicalTo($this->_expectedType))->thenReturn('baz');
    $exception = $this->exceptionFixture(array($this->_attributeName, $this->_expectedType, $this->_holderName, $typeRenderer));
    $expected = "Missing required attribute 'foo' for 'bar' - expected 'baz'.";

    $this->assertSame($expected, $exception->getMessage());
    $this->assertSame($typeRenderer, $exception->typeRenderer());
    Phake::verify($typeRenderer)->render($this->identicalTo($this->_expectedType));


    parent::testConstructor();
  }

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
