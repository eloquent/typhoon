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

use PHPUnit_Framework_TestCase;
use ReflectionClass;
use Typhoon\Primitive\String;
use Typhoon\Type\Exception\UnexpectedType;

class PrimitiveTest extends PHPUnit_Framework_TestCase
{
  /**
   * @covers Typhoon\ParameterList::typeAssertion
   */
  public function testTypeAssertion()
  {
    $value = 'foo';
    $type = $this->getMockForAbstractClass('Typhoon\Type');

    $primitive = $this->getMockForAbstractClass(
      __NAMESPACE__.'\Primitive',
      array(),
      '',
      false
    );
    $reflector = new ReflectionClass($primitive);
    $typeAssertionMethod = $reflector->getMethod('typeAssertion');
    $typeAssertionMethod->setAccessible(true);

    $this->assertInstanceOf('Typhoon\Assertion\Type', $typeAssertionMethod->invokeArgs($primitive, array($type, $value)));
  }

  /**
   * @covers Typhoon\Primitive::__construct
   * @covers Typhoon\Primitive::value
   * @covers Typhoon\Primitive::__toString
   */
  public function testPrimitive()
  {
    $value = 'foo';

    $type = $this->getMockForAbstractClass(__NAMESPACE__.'\Type');

    $primitive = $this->getMock(
      __NAMESPACE__.'\Primitive',
      array('type', 'typeAssertion'),
      array($value),
      '',
      false
    );
    $primitive
      ->expects($this->any())
      ->method('type')
      ->will($this->returnValue($type))
    ;

    $_this = $this;
    $primitive
      ->expects($this->once())
      ->method('typeAssertion')
      ->with($this->equalTo($type), $this->equalTo($value))
      ->will($this->returnCallback(function(Type $type, $value) use ($_this)
        {
          $assertion = $_this->getMock(
            __NAMESPACE__.'\Assertion\Type',
            array('assert'),
            array($type, $value)
          );
          $assertion
            ->expects($_this->once())
            ->method('assert')
          ;

          return $assertion;
        }
      ))
    ;

    $primitive->__construct($value);

    $this->assertEquals($value, $primitive->value());
    $this->assertEquals((string)$value, (string)$primitive);
  }

  /**
   * @covers Typhoon\Primitive::__construct
   * @covers Typhoon\Primitive::value
   * @covers Typhoon\Primitive::__toString
   */
  public function testPrimitiveFailure()
  {
    $value = 'foo';

    $type = $this->getMockForAbstractClass(__NAMESPACE__.'\Type');

    $primitive = $this->getMock(
      __NAMESPACE__.'\Primitive',
      array('type', 'typeAssertion'),
      array($value),
      '',
      false
    );
    $primitive
      ->expects($this->any())
      ->method('type')
      ->will($this->returnValue($type))
    ;

    $_this = $this;
    $primitive
      ->expects($this->once())
      ->method('typeAssertion')
      ->with($this->equalTo($type), $this->equalTo($value))
      ->will($this->returnCallback(function(Type $type, $value) use ($_this)
        {
          $assertion = $_this->getMock(
            __NAMESPACE__.'\Assertion\Type',
            array('assert'),
            array($type, $value)
          );
          $assertion
            ->expects($_this->once())
            ->method('assert')
            ->will($_this->throwException(new UnexpectedType($value, new String((string)$type))))
          ;

          return $assertion;
        }
      ))
    ;

    $this->setExpectedException(__NAMESPACE__.'\ParameterList\Exception\UnexpectedArgument');
    $primitive->__construct($value);
  }
}