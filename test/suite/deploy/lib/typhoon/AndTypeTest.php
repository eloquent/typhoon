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

use Phake;
use Typhoon\Test\TestCase;

class AndTypeTest extends TestCase
{
  protected function setUp()
  {
    $this->_andType = new AndType;
    $this->_typeA = Phake::mock(__NAMESPACE__.'\Type');
    $this->_typeB = Phake::mock(__NAMESPACE__.'\Type');
  }

  /**
   * @covers Typhoon\BaseCompositeType::addTyphoonType
   * @covers Typhoon\AndType::typhoonCheck
   */
  public function testAddCheckBothValid()
  {
    $value = 'foo';
    Phake::when($this->_typeA)->typhoonCheck($value)->thenReturn(true);
    Phake::when($this->_typeB)->typhoonCheck($value)->thenReturn(true);
    $this->_andType->addTyphoonType($this->_typeA);
    $this->_andType->addTyphoonType($this->_typeB);

    $this->assertTrue($this->_andType->typhoonCheck($value));
    Phake::verify($this->_typeA)->typhoonCheck($value);
    Phake::verify($this->_typeB)->typhoonCheck($value);
  }

  /**
   * @covers Typhoon\BaseCompositeType::addTyphoonType
   * @covers Typhoon\AndType::typhoonCheck
   */
  public function testAddCheckFirstValid()
  {
    $value = 'foo';
    Phake::when($this->_typeA)->typhoonCheck($value)->thenReturn(true);
    Phake::when($this->_typeB)->typhoonCheck($value)->thenReturn(false);
    $this->_andType->addTyphoonType($this->_typeA);
    $this->_andType->addTyphoonType($this->_typeB);

    $this->assertFalse($this->_andType->typhoonCheck($value));
    Phake::verify($this->_typeA)->typhoonCheck($value);
    Phake::verify($this->_typeB)->typhoonCheck($value);
  }

  /**
   * @covers Typhoon\BaseCompositeType::addTyphoonType
   * @covers Typhoon\AndType::typhoonCheck
   */
  public function testAddCheckNeitherValid()
  {
    $value = 'foo';
    Phake::when($this->_typeA)->typhoonCheck($value)->thenReturn(false);
    $this->_andType->addTyphoonType($this->_typeA);
    $this->_andType->addTyphoonType($this->_typeB);

    $this->assertFalse($this->_andType->typhoonCheck($value));
    Phake::verify($this->_typeA)->typhoonCheck($value);
    Phake::verify($this->_typeB, Phake::never())->typhoonCheck($this->anything());
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