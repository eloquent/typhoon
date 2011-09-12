<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ezzatron\Typhoon\Primitive;

use Phake;
use Ezzatron\Typhoon\Assertion\Exception\UnexpectedTypeException;
use Ezzatron\Typhoon\Type;
use Ezzatron\Typhoon\Typhoon;

class PrimitiveTest extends \Ezzatron\Typhoon\Test\TestCase
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
   * @covers Ezzatron\Typhoon\Primitive\Primitive::__construct
   * @covers Ezzatron\Typhoon\Primitive\Primitive::value
   * @covers Ezzatron\Typhoon\Primitive\Primitive::__toString
   * @covers Ezzatron\Typhoon\Primitive\Primitive::typeAssertion
   */
  public function testPrimitive()
  {
    $value = 'foo';
    $type = Phake::mock('Ezzatron\Typhoon\Type\Type');
    $typeAssertion = Phake::mock('Ezzatron\Typhoon\Assertion\TypeAssertion');

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

    Phake::inOrder(
      Phake::verify($typeAssertion)->setType($this->identicalTo($type))
      , Phake::verify($typeAssertion)->setValue($value)
      , Phake::verify($typeAssertion)->assert()
    );
  }

  /**
   * @covers Ezzatron\Typhoon\Primitive\Primitive::__construct
   */
  public function testPrimitiveFailure()
  {
    $value = 'foo';
    $type = Phake::mock('Ezzatron\Typhoon\Type\Type');
    $typeAssertion = Phake::mock('Ezzatron\Typhoon\Assertion\TypeAssertion');

    Phake::when($typeAssertion)->assert()->thenThrow(new UnexpectedTypeException($value, new String('typeName')));

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

    $this->setExpectedException('Ezzatron\Typhoon\Assertion\Exception\UnexpectedArgumentException');
    $primitive->__construct($value);
  }

  /**
   * @covers Ezzatron\Typhoon\Primitive\Primitive::typeAssertion
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

    $reflector = new \ReflectionObject($primitive);
    $method = $reflector->getMethod('typeAssertion');
    $method->setAccessible(true);

    $this->assertInstanceOf($expected, $method->invoke($primitive));
  }
}