<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ezzatron\Typhoon;

use Phake;
use Ezzatron\Typhoon\Test\TestCase;

class OrTypeTest extends TestCase
{
  protected function setUp()
  {
    $this->_orType = new OrType;
    $this->_typeA = Phake::mock(__NAMESPACE__.'\Type');
    $this->_typeB = Phake::mock(__NAMESPACE__.'\Type');
  }

  /**
   * @covers Ezzatron\Typhoon\BaseCompositeType::addTyphoonType
   * @covers Ezzatron\Typhoon\OrType::typhoonCheck
   */
  public function testAddCheckFirstValid()
  {
    $value = 'foo';
    Phake::when($this->_typeA)->typhoonCheck($value)->thenReturn(true);
    $this->_orType->addTyphoonType($this->_typeA);
    $this->_orType->addTyphoonType($this->_typeB);

    $this->assertTrue($this->_orType->typhoonCheck($value));
    Phake::verify($this->_typeA)->typhoonCheck($value);
    Phake::verify($this->_typeB, Phake::never())->typhoonCheck(Phake::anyParameters());
  }

  /**
   * @covers Ezzatron\Typhoon\BaseCompositeType::addTyphoonType
   * @covers Ezzatron\Typhoon\OrType::typhoonCheck
   */
  public function testAddCheckSecondValid()
  {
    $value = 'foo';
    Phake::when($this->_typeA)->typhoonCheck($value)->thenReturn(false);
    Phake::when($this->_typeB)->typhoonCheck($value)->thenReturn(true);
    $this->_orType->addTyphoonType($this->_typeA);
    $this->_orType->addTyphoonType($this->_typeB);

    $this->assertTrue($this->_orType->typhoonCheck($value));
    Phake::verify($this->_typeA)->typhoonCheck($value);
    Phake::verify($this->_typeB)->typhoonCheck($value);
  }

  /**
   * @covers Ezzatron\Typhoon\BaseCompositeType::addTyphoonType
   * @covers Ezzatron\Typhoon\OrType::typhoonCheck
   */
  public function testAddCheckNeitherValid()
  {
    $value = 'foo';
    Phake::when($this->_typeA)->typhoonCheck($value)->thenReturn(false);
    Phake::when($this->_typeB)->typhoonCheck($value)->thenReturn(false);
    $this->_orType->addTyphoonType($this->_typeA);
    $this->_orType->addTyphoonType($this->_typeB);

    $this->assertFalse($this->_orType->typhoonCheck($value));
    Phake::verify($this->_typeA)->typhoonCheck($value);
    Phake::verify($this->_typeB)->typhoonCheck($value);
  }

  /**
   * @covers Ezzatron\Typhoon\OrType
   */
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