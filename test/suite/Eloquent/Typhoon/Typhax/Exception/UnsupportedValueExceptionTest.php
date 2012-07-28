<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Typhax\Exception;

use Eloquent\Typhoon\Test\ExceptionTestCase;
use Phake;

class UnsupportedValueExceptionTest extends ExceptionTestCase
{
  /**
   * @return string
   */
  protected function exceptionClass()
  {
    return __NAMESPACE__.'\UnsupportedValueException';
  }

  /**
   * @return array
   */
  protected function defaultArguments()
  {
    return array($this->_value, null, null);
  }

  protected function setUp()
  {
    parent::setUp();

    $this->_value = 'foo';
  }

  /**
   * @covers Eloquent\Typhoon\Typhax\Exception\UnsupportedValueException::__construct
   * @group exceptions
   * @group core
   */
  public function testConstructor()
  {
    $this->assertSame("Cannot render value of unsupported type 'string'.", $this->exceptionFixture()->getMessage());


    $type = Phake::mock('Eloquent\Typhoon\Type\Type');
    $typeInspector = Phake::mock('Eloquent\Typhoon\Type\Inspector\TypeInspector');
    $typeRenderer = Phake::mock('Eloquent\Typhoon\Type\Renderer\TypeRenderer');
    Phake::when($typeInspector)->typeOf($this->_value)->thenReturn($type);
    Phake::when($typeRenderer)->render($this->identicalTo($type))->thenReturn('bar');
    $expected = "Cannot render value of unsupported type 'bar'.";

    $this->assertSame($expected, $this->exceptionFixture(array($this->_value, $typeInspector, $typeRenderer))->getMessage());
    Phake::verify($typeInspector)->typeOf($this->_value);
    Phake::verify($typeRenderer)->render($this->identicalTo($type));


    parent::testConstructor();
  }

  /**
   * @var scalar
   */
  protected $_value;
}
