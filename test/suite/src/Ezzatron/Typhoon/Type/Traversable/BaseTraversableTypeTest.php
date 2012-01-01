<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ezzatron\Typhoon\Type\Traversable;

use Phake;
use Ezzatron\Typhoon\Type\MixedType;

class BaseTraversableTypeTest extends \Ezzatron\Typhoon\Test\TestCase
{
  protected function setUp()
  {
    parent::setUp();

    $this->_type = Phake::partialMock(__NAMESPACE__.'\BaseTraversableType');
  }

  /**
   * @covers Ezzatron\Typhoon\Type\Traversable\BaseTraversableType::__construct
   * @covers Ezzatron\Typhoon\Type\Traversable\BaseTraversableType::setTyphoonSubType
   * @covers Ezzatron\Typhoon\Type\Traversable\BaseTraversableType::typhoonSubType
   * @group type
   * @group traversable-type
   * @group core
   */
  public function testTyphoonSubType()
  {
    $this->assertEquals(new MixedType, $this->_type->typhoonSubType());

    $type = Phake::mock('Ezzatron\Typhoon\Type\Type');
    $this->_type->setTyphoonSubType($type);

    $this->assertSame($type, $this->_type->typhoonSubType());
  }

  /**
   * @covers Ezzatron\Typhoon\Type\Traversable\BaseTraversableType::__construct
   * @covers Ezzatron\Typhoon\Type\Traversable\BaseTraversableType::setTyphoonKeyType
   * @covers Ezzatron\Typhoon\Type\Traversable\BaseTraversableType::typhoonKeyType
   * @group type
   * @group traversable-type
   * @group core
   */
  public function testTyphoonKeyType()
  {
    $this->assertEquals(new MixedType, $this->_type->typhoonKeyType());

    $type = Phake::mock('Ezzatron\Typhoon\Type\Type');
    $this->_type->setTyphoonKeyType($type);

    $this->assertSame($type, $this->_type->typhoonKeyType());
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
   * @covers Ezzatron\Typhoon\Type\Traversable\BaseTraversableType::typhoonCheck
   * @dataProvider typhoonCheckData
   * @group type
   * @group traversable-type
   * @group core
   */
  public function testTyphoonCheck($value, $expected, $primaryResult, array $subResults, array $keyResults)
  {
    Phake::when($this->_type)->checkPrimary($value)->thenReturn($primaryResult);

    if ($subResults)
    {
      $subType = Phake::mock('Ezzatron\Typhoon\Type\Type');
      $previous = Phake::when($subType)->typhoonCheck(Phake::anyParameters());
      foreach ($subResults as $subResult)
      {
        $previous = $previous->thenReturn($subResult);
      }

      $this->_type->setTyphoonSubType($subType);
    }

    if ($keyResults)
    {
      $keyType = Phake::mock('Ezzatron\Typhoon\Type\Type');
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
