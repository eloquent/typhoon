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

class OrTypeTest extends TestCase
{
  protected function setUp()
  {
    $this->_orType = new OrType;
    $this->_typeA = $this->getMock(__NAMESPACE__.'\Type');
    $this->_typeB = $this->getMock(__NAMESPACE__.'\Type');
  }

  /**
   * @covers Typhoon\BaseCompositeType::addTyphoonType
   * @covers Typhoon\OrType::check
   */
  public function testAddCheckFirstValid()
  {
    $value = 'foo';
    $this->_typeA
      ->expects($this->once())
      ->method('check')
      ->with($this->equalTo($value))
      ->will($this->returnValue(true))
    ;
    $this->_typeB
      ->expects($this->never())
      ->method('check')
    ;
    $this->_orType->addTyphoonType($this->_typeA);
    $this->_orType->addTyphoonType($this->_typeB);

    $this->assertTrue($this->_orType->check($value));
  }

  /**
   * @covers Typhoon\BaseCompositeType::addTyphoonType
   * @covers Typhoon\OrType::check
   */
  public function testAddCheckSecondValid()
  {
    $value = 'foo';
    $this->_typeA
      ->expects($this->once())
      ->method('check')
      ->with($this->equalTo($value))
      ->will($this->returnValue(false))
    ;
    $this->_typeB
      ->expects($this->once())
      ->method('check')
      ->with($this->equalTo($value))
      ->will($this->returnValue(true))
    ;
    $this->_orType->addTyphoonType($this->_typeA);
    $this->_orType->addTyphoonType($this->_typeB);

    $this->assertTrue($this->_orType->check($value));
  }

  /**
   * @covers Typhoon\BaseCompositeType::addTyphoonType
   * @covers Typhoon\OrType::check
   */
  public function testAddCheckNeitherValid()
  {
    $value = 'foo';
    $this->_typeA
      ->expects($this->once())
      ->method('check')
      ->with($this->equalTo($value))
      ->will($this->returnValue(false))
    ;
    $this->_typeB
      ->expects($this->once())
      ->method('check')
      ->with($this->equalTo($value))
      ->will($this->returnValue(false))
    ;
    $this->_orType->addTyphoonType($this->_typeA);
    $this->_orType->addTyphoonType($this->_typeB);

    $this->assertFalse($this->_orType->check($value));
  }

  public function testImplementsType()
  {
    $this->assertInstanceOf(__NAMESPACE__.'\CompositeType', $this->_orType);
  }

  /**
   * @var OrType
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