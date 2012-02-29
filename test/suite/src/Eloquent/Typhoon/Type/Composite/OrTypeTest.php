<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Type\Composite;

use Phake;

class OrTypeTest extends \Eloquent\Typhoon\Test\TestCase
{
  protected function setUp()
  {
    $this->_orType = new OrType;
    $this->_typeA = Phake::mock('Eloquent\Typhoon\Type\Type');
    $this->_typeB = Phake::mock('Eloquent\Typhoon\Type\Type');
  }

  /**
   * @covers Eloquent\Typhoon\Type\Composite\BaseCompositeType::addTyphoonType
   * @covers Eloquent\Typhoon\Type\Composite\OrType::typhoonCheck
   * @group type
   * @group composite-type
   * @group core
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
   * @covers Eloquent\Typhoon\Type\Composite\BaseCompositeType::addTyphoonType
   * @covers Eloquent\Typhoon\Type\Composite\OrType::typhoonCheck
   * @group type
   * @group composite-type
   * @group core
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
   * @covers Eloquent\Typhoon\Type\Composite\BaseCompositeType::addTyphoonType
   * @covers Eloquent\Typhoon\Type\Composite\OrType::typhoonCheck
   * @group type
   * @group composite-type
   * @group core
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
   * @covers Eloquent\Typhoon\Type\Composite\OrType
   * @group type
   * @group composite-type
   * @group core
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
