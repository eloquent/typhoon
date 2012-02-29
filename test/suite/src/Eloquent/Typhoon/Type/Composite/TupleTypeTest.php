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

class TupleTypeTest extends \Eloquent\Typhoon\Test\TestCase
{
  protected function setUp()
  {
    $this->_tupleType = new TupleType;
    $this->_typeA = Phake::mock('Eloquent\Typhoon\Type\Type');
    $this->_typeB = Phake::mock('Eloquent\Typhoon\Type\Type');
  }

  /**
   * @covers Eloquent\Typhoon\Type\Composite\BaseCompositeType::addTyphoonType
   * @covers Eloquent\Typhoon\Type\Composite\TupleType::typhoonCheck
   * @group type
   * @group composite-type
   * @group core
   */
  public function testAddCheckAllValid()
  {
    $value = array('foo', 'bar');
    Phake::when($this->_typeA)->typhoonCheck('foo')->thenReturn(true);
    Phake::when($this->_typeB)->typhoonCheck('bar')->thenReturn(true);
    $this->_tupleType->addTyphoonType($this->_typeA);
    $this->_tupleType->addTyphoonType($this->_typeB);

    $this->assertTrue($this->_tupleType->typhoonCheck($value));
    Phake::verify($this->_typeA)->typhoonCheck('foo');
    Phake::verify($this->_typeB)->typhoonCheck('bar');
    Phake::verify($this->_typeA, Phake::never())->typhoonCheck('bar');
    Phake::verify($this->_typeB, Phake::never())->typhoonCheck('foo');
  }

  /**
   * @covers Eloquent\Typhoon\Type\Composite\BaseCompositeType::addTyphoonType
   * @covers Eloquent\Typhoon\Type\Composite\TupleType::typhoonCheck
   * @group type
   * @group composite-type
   * @group core
   */
  public function testAddCheckEmptyValid()
  {
    $value = array();

    $this->assertTrue($this->_tupleType->typhoonCheck($value));
  }

  /**
   * @covers Eloquent\Typhoon\Type\Composite\BaseCompositeType::addTyphoonType
   * @covers Eloquent\Typhoon\Type\Composite\TupleType::typhoonCheck
   * @group type
   * @group composite-type
   * @group core
   */
  public function testAddCheckNotArray()
  {
    $value = 'foo';

    $this->assertFalse($this->_tupleType->typhoonCheck($value));
  }

  /**
   * @covers Eloquent\Typhoon\Type\Composite\BaseCompositeType::addTyphoonType
   * @covers Eloquent\Typhoon\Type\Composite\TupleType::typhoonCheck
   * @group type
   * @group composite-type
   * @group core
   */
  public function testAddCheckFirstInvalid()
  {
    $value = array('foo', 'bar');
    Phake::when($this->_typeA)->typhoonCheck('foo')->thenReturn(false);
    $this->_tupleType->addTyphoonType($this->_typeA);
    $this->_tupleType->addTyphoonType($this->_typeB);

    $this->assertFalse($this->_tupleType->typhoonCheck($value));
    Phake::verify($this->_typeA)->typhoonCheck('foo');
    Phake::verify($this->_typeA, Phake::never())->typhoonCheck('bar');
    Phake::verify($this->_typeB, Phake::never())->typhoonCheck(Phake::anyParameters());
  }

  /**
   * @covers Eloquent\Typhoon\Type\Composite\BaseCompositeType::addTyphoonType
   * @covers Eloquent\Typhoon\Type\Composite\TupleType::typhoonCheck
   * @group type
   * @group composite-type
   * @group core
   */
  public function testAddCheckLastInvalid()
  {
    $value = array('foo', 'bar');
    Phake::when($this->_typeA)->typhoonCheck('foo')->thenReturn(true);
    Phake::when($this->_typeB)->typhoonCheck('bar')->thenReturn(false);
    $this->_tupleType->addTyphoonType($this->_typeA);
    $this->_tupleType->addTyphoonType($this->_typeB);

    $this->assertFalse($this->_tupleType->typhoonCheck($value));
    Phake::verify($this->_typeA)->typhoonCheck('foo');
    Phake::verify($this->_typeB)->typhoonCheck('bar');
    Phake::verify($this->_typeA, Phake::never())->typhoonCheck('bar');
    Phake::verify($this->_typeB, Phake::never())->typhoonCheck('foo');
  }

  /**
   * @covers Eloquent\Typhoon\Type\Composite\BaseCompositeType::addTyphoonType
   * @covers Eloquent\Typhoon\Type\Composite\TupleType::typhoonCheck
   * @group type
   * @group composite-type
   * @group core
   */
  public function testAddCheckTooFew()
  {
    $value = array('foo');
    $this->_tupleType->addTyphoonType($this->_typeA);
    $this->_tupleType->addTyphoonType($this->_typeB);

    $this->assertFalse($this->_tupleType->typhoonCheck($value));
    Phake::verify($this->_typeA, Phake::never())->typhoonCheck(Phake::anyParameters());
    Phake::verify($this->_typeB, Phake::never())->typhoonCheck(Phake::anyParameters());
  }

  /**
   * @covers Eloquent\Typhoon\Type\Composite\BaseCompositeType::addTyphoonType
   * @covers Eloquent\Typhoon\Type\Composite\TupleType::typhoonCheck
   * @group type
   * @group composite-type
   * @group core
   */
  public function testAddCheckTooMany()
  {
    $value = array('foo', 'bar', 'baz');
    $this->_tupleType->addTyphoonType($this->_typeA);
    $this->_tupleType->addTyphoonType($this->_typeB);

    $this->assertFalse($this->_tupleType->typhoonCheck($value));
    Phake::verify($this->_typeA, Phake::never())->typhoonCheck(Phake::anyParameters());
    Phake::verify($this->_typeB, Phake::never())->typhoonCheck(Phake::anyParameters());
  }

  /**
   * @covers Eloquent\Typhoon\Type\Composite\BaseCompositeType::addTyphoonType
   * @covers Eloquent\Typhoon\Type\Composite\TupleType::typhoonCheck
   * @group type
   * @group composite-type
   * @group core
   */
  public function testAddCheckInvalidKeys()
  {
    $value = array(1 => 'foo', 2 => 'bar');
    $this->_tupleType->addTyphoonType($this->_typeA);
    $this->_tupleType->addTyphoonType($this->_typeB);

    $this->assertFalse($this->_tupleType->typhoonCheck($value));
    Phake::verify($this->_typeA, Phake::never())->typhoonCheck(Phake::anyParameters());
    Phake::verify($this->_typeB, Phake::never())->typhoonCheck(Phake::anyParameters());
  }

  /**
   * @covers Eloquent\Typhoon\Type\Composite\TupleType
   * @group type
   * @group composite-type
   * @group core
   */
  public function testImplementsType()
  {
    $this->assertInstanceOf(__NAMESPACE__.'\CompositeType', $this->_tupleType);
  }

  /**
   * @var TupleType
   */
  protected $_tupleType;

  /**
   * @var Type
   */
  protected $_typeA;

  /**
   * @var Type
   */
  protected $_typeB;
}