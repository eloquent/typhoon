<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Type\SubTyped;

use Phake;
use Eloquent\Typhoon\Type\MixedType;

class BaseTraversableTypeTest extends \Eloquent\Typhoon\Test\TestCase
{
  protected function setUp()
  {
    parent::setUp();

    $this->_type = Phake::partialMock(__NAMESPACE__.'\BaseTraversableType');
  }

  /**
   * @covers Eloquent\Typhoon\Type\SubTyped\BaseTraversableType::__construct
   * @covers Eloquent\Typhoon\Type\SubTyped\BaseTraversableType::setTyphoonSubType
   * @covers Eloquent\Typhoon\Type\SubTyped\BaseTraversableType::typhoonSubType
   * @covers Eloquent\Typhoon\Type\SubTyped\BaseTraversableType::typhoonTypes
   * @group type
   * @group sub-typed-type
   * @group traversable-type
   * @group core
   */
  public function testTyphoonSubType()
  {
    $this->assertEquals(new MixedType, $this->_type->typhoonSubType());

    $type = Phake::mock('Eloquent\Typhoon\Type\NamedType');
    $this->_type->setTyphoonSubType($type);

    $this->assertSame($type, $this->_type->typhoonSubType());
    $this->assertSame(array($type), $this->_type->typhoonTypes());
  }

  /**
   * @covers Eloquent\Typhoon\Type\SubTyped\BaseTraversableType::__construct
   * @covers Eloquent\Typhoon\Type\SubTyped\BaseTraversableType::setTyphoonKeyType
   * @covers Eloquent\Typhoon\Type\SubTyped\BaseTraversableType::typhoonKeyType
   * @covers Eloquent\Typhoon\Type\SubTyped\BaseTraversableType::typhoonTypes
   * @group type
   * @group sub-typed-type
   * @group traversable-type
   * @group core
   */
  public function testTyphoonKeyType()
  {
    $this->assertEquals(new MixedType, $this->_type->typhoonKeyType());

    $type = Phake::mock('Eloquent\Typhoon\Type\NamedType');
    $this->_type->setTyphoonKeyType($type);

    $this->assertSame($type, $this->_type->typhoonKeyType());
    $this->assertEquals(array($type, new MixedType), $this->_type->typhoonTypes());
  }

  /**
   * @covers Eloquent\Typhoon\Type\SubTyped\BaseTraversableType::setTyphoonTypes
   * @covers Eloquent\Typhoon\Type\SubTyped\BaseTraversableType::typhoonTypes
   * @group type
   * @group sub-typed-type
   * @group traversable-type
   * @group core
   */
  public function testsetTyphoonTypesSubOnly()
  {
    $this->assertEquals(new MixedType, $this->_type->typhoonSubType());

    $type = Phake::mock('Eloquent\Typhoon\Type\NamedType');
    $this->_type->setTyphoonTypes(array($type));

    $this->assertSame($type, $this->_type->typhoonSubType());
    $this->assertEquals(array($type), $this->_type->typhoonTypes());
  }

  /**
   * @covers Eloquent\Typhoon\Type\SubTyped\BaseTraversableType::setTyphoonTypes
   * @covers Eloquent\Typhoon\Type\SubTyped\BaseTraversableType::typhoonTypes
   * @group type
   * @group sub-typed-type
   * @group traversable-type
   * @group core
   */
  public function testsetTyphoonTypesKeyAndSub()
  {
    $this->assertEquals(new MixedType, $this->_type->typhoonSubType());

    $typeA = Phake::mock('Eloquent\Typhoon\Type\NamedType');
    $typeB = Phake::mock('Eloquent\Typhoon\Type\NamedType');
    $this->_type->setTyphoonTypes(array($typeA, $typeB));

    $this->assertSame($typeA, $this->_type->typhoonKeyType());
    $this->assertSame($typeB, $this->_type->typhoonSubType());
    $this->assertSame(array($typeA, $typeB), $this->_type->typhoonTypes());
  }

  /**
   * @covers Eloquent\Typhoon\Type\SubTyped\BaseTraversableType::typhoonTypes
   * @group type
   * @group sub-typed-type
   * @group traversable-type
   * @group core
   */
  public function testsetTyphoonTypesNeither()
  {
    $this->assertSame(array(), $this->_type->typhoonTypes());
  }

  /**
   * @covers Eloquent\Typhoon\Type\SubTyped\BaseTraversableType::setTyphoonTypes
   * @group type
   * @group sub-typed-type
   * @group traversable-type
   * @group core
   */
  public function testsetTyphoonTypesFailure()
  {
    $type = Phake::mock('Eloquent\Typhoon\Type\NamedType');

    $this->setExpectedException('Eloquent\Typhoon\Type\SubTyped\Exception\UnexpectedSubTypeException');
    $this->_type->setTyphoonTypes(array($type, $type, $type));
  }

  /**
   * @return array
   */
  public function typhoonCheckData()
  {
    $data = array();

    // #0: primary pass and no sub-type or key type
    $value = array();
    $expected = true;
    $primaryResult = true;
    $subResults = array();
    $keyResults = array();
    $data[] = array($value, $expected, $primaryResult, $subResults, $keyResults);

    // #1: primary failure and no sub-type or key type
    $value = array();
    $expected = false;
    $primaryResult = false;
    $subResults = array();
    $keyResults = array();
    $data[] = array($value, $expected, $primaryResult, $subResults, $keyResults);

    // #2: primary pass, sub-type multiple passes, no key type
    $value = array('foo', 'bar');
    $expected = true;
    $primaryResult = true;
    $subResults = array(true, true);
    $keyResults = array();
    $data[] = array($value, $expected, $primaryResult, $subResults, $keyResults);

    // #3: primary pass, sub-type pass then fail, no key type
    $value = array('foo', 'bar');
    $expected = false;
    $primaryResult = true;
    $subResults = array(true, false);
    $keyResults = array();
    $data[] = array($value, $expected, $primaryResult, $subResults, $keyResults);

    // #4: primary pass, sub-type fail, no key type
    $value = array('foo', 'bar');
    $expected = false;
    $primaryResult = true;
    $subResults = array(false);
    $keyResults = array();
    $data[] = array($value, $expected, $primaryResult, $subResults, $keyResults);

    // #5: primary pass, no sub-type, key type multiple passes
    $value = array('foo', 'bar');
    $expected = true;
    $primaryResult = true;
    $subResults = array();
    $keyResults = array(true, true);
    $data[] = array($value, $expected, $primaryResult, $subResults, $keyResults);

    // #6: primary pass, no sub-type, key type pass then fail
    $value = array('foo', 'bar');
    $expected = false;
    $primaryResult = true;
    $subResults = array();
    $keyResults = array(true, false);
    $data[] = array($value, $expected, $primaryResult, $subResults, $keyResults);

    // #7: primary pass, no sub-type, key type fail
    $value = array('foo', 'bar');
    $expected = false;
    $primaryResult = true;
    $subResults = array();
    $keyResults = array(false);
    $data[] = array($value, $expected, $primaryResult, $subResults, $keyResults);

    // #8: primary pass, sub-type multiple passes, key type multiple passes
    $value = array('foo', 'bar');
    $expected = true;
    $primaryResult = true;
    $subResults = array(true, true);
    $keyResults = array(true, true);
    $data[] = array($value, $expected, $primaryResult, $subResults, $keyResults);

    // #9: primary pass, sub-type multiple passes, key type pass then fail
    $value = array('foo', 'bar');
    $expected = false;
    $primaryResult = true;
    $subResults = array(true, true);
    $keyResults = array(true, false);
    $data[] = array($value, $expected, $primaryResult, $subResults, $keyResults);

    // #10: primary pass, sub-type pass then fail, key type pass
    $value = array('foo', 'bar');
    $expected = false;
    $primaryResult = true;
    $subResults = array(true, false);
    $keyResults = array(true);
    $data[] = array($value, $expected, $primaryResult, $subResults, $keyResults);

    // #11: primary pass, sub-type pass, key type fail
    $value = array('foo', 'bar');
    $expected = false;
    $primaryResult = true;
    $subResults = array(true);
    $keyResults = array(false);
    $data[] = array($value, $expected, $primaryResult, $subResults, $keyResults);

    return $data;
  }

  /**
   * @covers Eloquent\Typhoon\Type\SubTyped\BaseTraversableType::typhoonCheck
   * @dataProvider typhoonCheckData
   * @group type
   * @group sub-typed-type
   * @group traversable-type
   * @group core
   */
  public function testTyphoonCheck($value, $expected, $primaryResult, array $subResults, array $keyResults)
  {
    Phake::when($this->_type)->checkPrimary($value)->thenReturn($primaryResult);

    if ($subResults)
    {
      $subType = Phake::mock('Eloquent\Typhoon\Type\NamedType');
      $previous = Phake::when($subType)->typhoonCheck(Phake::anyParameters());
      foreach ($subResults as $subResult)
      {
        $previous = $previous->thenReturn($subResult);
      }

      $this->_type->setTyphoonSubType($subType);
    }

    if ($keyResults)
    {
      $keyType = Phake::mock('Eloquent\Typhoon\Type\NamedType');
      $previous = Phake::when($keyType)->typhoonCheck(Phake::anyParameters());
      foreach ($keyResults as $keyResult)
      {
        $previous = $previous->thenReturn($keyResult);
      }

      $this->_type->setTyphoonKeyType($keyType);
    }

    $this->assertEquals($expected, $this->_type->typhoonCheck($value));

    if ($subResults)
    {
      $calls = array();
      foreach ($subResults as $key => $subResult)
      {
        $calls[] = Phake::verify($subType)->typhoonCheck($value[$key]);
      }

      call_user_func_array('Phake::inOrder', $calls);
    }

    if ($keyResults)
    {
      $calls = array();
      foreach ($keyResults as $key => $keyResult)
      {
        $calls[] = Phake::verify($keyType)->typhoonCheck($key);
      }

      call_user_func_array('Phake::inOrder', $calls);
    }
  }

  /**
   * @var BaseTraversableType
   */
  protected $_type;
}
