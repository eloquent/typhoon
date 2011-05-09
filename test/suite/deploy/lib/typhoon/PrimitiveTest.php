<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Typhoon;

use Typhoon;
use Typhoon\Assertion\Exception\UnexpectedType;
use Typhoon\Primitive\String;
use Typhoon\Test\TestCase;
use Typhoon\Type;

class PrimitiveTest extends TestCase
{
  /**
   * @return Primitive
   */
  public function primitiveFixture()
  {
    return $this->getMock(
      __NAMESPACE__.'\Primitive',
      array('type', 'typeAssertion'),
      array(),
      '',
      false
    );
  }

  /**
   * @covers Typhoon\Primitive::__construct
   * @covers Typhoon\Primitive::value
   * @covers Typhoon\Primitive::__toString
   */
  public function testPrimitive()
  {
    $type = $this->getMockForAbstractClass(__NAMESPACE__.'\Type');
    $value = 'foo';

    $typeAssertion = $this->getMock(
      __NAMESPACE__.'\Assertion\Type',
      array('setType', 'setValue', 'assert')
    );
    $typeAssertion
      ->expects($this->once())
      ->method('setType')
      ->with($this->equalTo($type))
    ;
    $typeAssertion
      ->expects($this->once())
      ->method('setValue')
      ->with($this->equalTo($value))
    ;
    $typeAssertion
      ->expects($this->once())
      ->method('assert')
    ;

    $primitive = $this->primitiveFixture();
    $primitive
      ->expects($this->once())
      ->method('typeAssertion')
      ->will($this->returnValue($typeAssertion))
    ;
    $primitive
      ->expects($this->once())
      ->method('type')
      ->will($this->returnValue($type))
    ;

    $primitive->__construct($value);

    $this->assertEquals($value, $primitive->value());
    $this->assertEquals((string)$value, (string)$primitive);
  }

  /**
   * @covers Typhoon\Primitive::__construct
   */
  public function testPrimitiveFailure()
  {
    $type = $this->getMockForAbstractClass(__NAMESPACE__.'\Type');
    $value = 'foo';

    $typeAssertion = $this->getMock(
      __NAMESPACE__.'\Assertion\Type',
      array('setType', 'setValue', 'assert')
    );
    $typeAssertion
      ->expects($this->once())
      ->method('assert')
      ->will($this->throwException(new UnexpectedType($value, new String('typeName'))))
    ;

    $primitive = $this->primitiveFixture();
    $primitive
      ->expects($this->once())
      ->method('typeAssertion')
      ->will($this->returnValue($typeAssertion))
    ;
    $primitive
      ->expects($this->exactly(2))
      ->method('type')
      ->will($this->returnValue($type))
    ;

    $this->setExpectedException(__NAMESPACE__.'\Assertion\Exception\UnexpectedArgument');
    $primitive->__construct($value);
  }

  /**
   * @covers Typhoon\Primitive::typeAssertion
   */
  public function testTypeAssertion()
  {
    $primitive = $this->getMock(
      __NAMESPACE__.'\Primitive',
      array('type'),
      array(),
      '',
      false
    );
    $expected = get_class(Typhoon::instance()->typeAssertion());

    $this->assertInstanceOf($expected, $primitive->typeAssertion());
  }
}