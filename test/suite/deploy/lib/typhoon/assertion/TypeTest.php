<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Typhoon\Assertion;

use PHPUnit_Framework_TestCase;
use stdClass;

class TypeTest extends PHPUnit_Framework_TestCase
{
  public function testImplementsInterface()
  {
    $type = $this->getMockForAbstractClass('Typhoon\Type');
    $value = 'foo';

    $this->assertInstanceOf('Typhoon\Assertion', new Type($type, $value));
  }

  /**
   * @covers Typhoon\Assertion\Type::__construct
   * @covers Typhoon\Assertion\Type::assert
   */
  public function testAssertion()
  {
    $value = 'foo';
    $type = $this->getMockForAbstractClass('Typhoon\Type');
    $type
      ->expects($this->once())
      ->method('check')
      ->with($this->equalTo($value))
      ->will($this->returnValue(true))
    ;

    $assertion = new Type($type, $value);
    $assertion->assert();
  }

  /**
   * @covers Typhoon\Assertion\Type::__construct
   * @covers Typhoon\Assertion\Type::assert
   */
  public function testAssertionFailure()
  {
    $value = 'foo';
    $type = $this->getMockForAbstractClass('Typhoon\Type');
    $type
      ->expects($this->once())
      ->method('check')
      ->with($this->equalTo($value))
      ->will($this->returnValue(false))
    ;

    $assertion = new Type($type, $value);

    $this->setExpectedException('Typhoon\Type\Exception\UnexpectedType');
    $assertion->assert();
  }
}