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
use Ezzatron\Typhoon\Assertion\TypeAssertion;
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
   * @group primitive
   * @group core
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
   * @group primitive
   * @group core
   */
  public function testPrimitiveFailure()
  {
    $type = Phake::mock('Ezzatron\Typhoon\Type\Type');
    $typeAssertion = Phake::mock('Ezzatron\Typhoon\Assertion\TypeAssertion');

    Phake::when($typeAssertion)->assert()->thenThrow(new UnexpectedTypeException(new String('typeName'), new String('expectedTypeName')));

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

    $this->setExpectedException('Ezzatron\Typhoon\Assertion\Exception\UnexpectedArgumentException');
    $primitive->__construct('foo');
  }

  /**
   * @covers Ezzatron\Typhoon\Primitive\Primitive::typeAssertion
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
