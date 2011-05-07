<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Typhoon\Type\Exception;

use Typhoon;
use Typhoon\Test\ExceptionTestCase;

class UnexpectedTypeTest extends ExceptionTestCase
{
  /**
   * @return string
   */
  protected function exceptionClass()
  {
    return __NAMESPACE__.'\UnexpectedType';
  }

  /**
   * @return array
   */
  protected function defaultArguments()
  {
    return array($this->_value, $this->typeFixture());
  }

  /**
   * @return Type
   */
  protected function typeFixture()
  {
    $type = $this->getMockForAbstractClass('Typhoon\Type');
    $type
      ->expects($this->once())
      ->method('__toString')
      ->will($this->returnValue($this->_expectedTypeName))
    ;

    return $type;
  }

  protected function setUp()
  {
    $this->_value = 'foo';
    $this->_expectedTypeName = 'foo';
  }

  /**
   * @covers Typhoon\Type\Exception\UnexpectedType::__construct
   */
  public function testConstructor()
  {
    $this->assertEquals("Unexpected type - expected '".$this->_expectedTypeName."'.", $this->exceptionFixture()->getMessage());

    parent::testConstructor();
  }

  /**
   * @covers Typhoon\Type\Exception\UnexpectedType::expectedType
   */
  public function testExpectedType()
  {
    $expectedType = $this->typeFixture();

    $this->assertSame($expectedType, $this->exceptionFixture(array($this->_value, $expectedType))->expectedType());
  }

  /**
   * @var mixed
   */
  protected $_value;

  /**
   * @var string
   */
  protected $_expectedTypeName;
}