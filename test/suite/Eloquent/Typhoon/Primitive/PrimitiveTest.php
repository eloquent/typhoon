<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Primitive;

use Phake;
use Eloquent\Typhoon\Assertion\Exception\UnexpectedTypeException;
use Eloquent\Typhoon\Assertion\TypeAssertion;
use Eloquent\Typhoon\Type;
use Eloquent\Typhoon\Typhoon;

class PrimitiveTest extends \Eloquent\Typhoon\Test\TestCase
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
   * @covers Eloquent\Typhoon\Primitive\Primitive::__construct
   * @covers Eloquent\Typhoon\Primitive\Primitive::value
   * @covers Eloquent\Typhoon\Primitive\Primitive::__toString
   * @covers Eloquent\Typhoon\Primitive\Primitive::typeAssertion
   * @group primitive
   * @group core
   */
  public function testPrimitive()
  {
    $value = 'foo';
    $type = Phake::mock('Eloquent\Typhoon\Type\NamedType');
    $typeAssertion = Phake::mock('Eloquent\Typhoon\Assertion\TypeAssertion');

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
   * @covers Eloquent\Typhoon\Primitive\Primitive::__construct
   * @group primitive
   * @group core
   */
  public function testPrimitiveFailure()
  {
    $type = Phake::mock('Eloquent\Typhoon\Type\NamedType');
    $expectedType = Phake::mock('Eloquent\Typhoon\Type\NamedType');
    $typeAssertion = Phake::mock('Eloquent\Typhoon\Assertion\TypeAssertion');

    Phake::when($typeAssertion)->assert()->thenThrow(new UnexpectedTypeException($type, $expectedType));

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

    $this->setExpectedException('Eloquent\Typhoon\Assertion\Exception\UnexpectedArgumentException');
    $primitive->__construct('foo');
  }

  /**
   * @covers Eloquent\Typhoon\Primitive\Primitive::typeAssertion
   * @group primitive
   * @group core
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
    $expected = new TypeAssertion;

    $reflector = new \ReflectionObject($primitive);
    $method = $reflector->getMethod('typeAssertion');
    $method->setAccessible(true);

    $this->assertEquals($expected, $method->invoke($primitive));
  }
}
