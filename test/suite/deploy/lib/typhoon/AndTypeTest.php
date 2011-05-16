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

use Typhoon\Test\TestCase;

class AndTypeTest extends TestCase
{
  protected function setUp()
  {
    $this->_andType = new AndType;
    $this->_typeA = $this->getMock(__NAMESPACE__.'\Type');
    $this->_typeB = $this->getMock(__NAMESPACE__.'\Type');
  }

  /**
   * @covers Typhoon\BaseCompositeType::addTyphoonType
   * @covers Typhoon\AndType::typhoonCheck
   */
  public function testAddCheckBothValid()
  {
    $value = 'foo';
    $this->_typeA
      ->expects($this->once())
      ->method('typhoonCheck')
      ->with($this->equalTo($value))
      ->will($this->returnValue(true))
    ;
    $this->_typeB
      ->expects($this->once())
      ->method('typhoonCheck')
      ->with($this->equalTo($value))
      ->will($this->returnValue(true))
    ;
    $this->_andType->addTyphoonType($this->_typeA);
    $this->_andType->addTyphoonType($this->_typeB);

    $this->assertTrue($this->_andType->typhoonCheck($value));
  }

  /**
   * @covers Typhoon\BaseCompositeType::addTyphoonType
   * @covers Typhoon\AndType::typhoonCheck
   */
  public function testAddCheckFirstValid()
  {
    $value = 'foo';
    $this->_typeA
      ->expects($this->once())
      ->method('typhoonCheck')
      ->with($this->equalTo($value))
      ->will($this->returnValue(true))
    ;
    $this->_typeB
      ->expects($this->once())
      ->method('typhoonCheck')
      ->with($this->equalTo($value))
      ->will($this->returnValue(false))
    ;
    $this->_andType->addTyphoonType($this->_typeA);
    $this->_andType->addTyphoonType($this->_typeB);

    $this->assertFalse($this->_andType->typhoonCheck($value));
  }

  /**
   * @covers Typhoon\BaseCompositeType::addTyphoonType
   * @covers Typhoon\AndType::typhoonCheck
   */
  public function testAddCheckNeitherValid()
  {
    $value = 'foo';
    $this->_typeA
      ->expects($this->once())
      ->method('typhoonCheck')
      ->with($this->equalTo($value))
      ->will($this->returnValue(false))
    ;
    $this->_typeB
      ->expects($this->never())
      ->method('typhoonCheck')
    ;
    $this->_andType->addTyphoonType($this->_typeA);
    $this->_andType->addTyphoonType($this->_typeB);

    $this->assertFalse($this->_andType->typhoonCheck($value));
  }

  public function testImplementsType()
  {
    $this->assertInstanceOf(__NAMESPACE__.'\CompositeType', $this->_andType);
  }

  /**
   * @var AndType
   */
  protected $_orType;

  /**
   * @var Type
   */
  protected $_typeA;

  /**
   * @var Type
   */
  protected $_typeB;
}