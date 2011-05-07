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
use Typhoon\TypeRegistry\Exception\UnregisteredType;
use Typhoon\TypeRegistry\Exception\UnregisteredTypeAlias;

class TypeRegistryTest extends PHPUnit_Framework_TestCase
{
  /**
   * @return array
   */
  public function defaultTypes()
  {
    return array(
      array('boolean', 'Typhoon\Type\Boolean'),
      array('integer', 'Typhoon\Type\Integer'),
      array('mixed', 'Typhoon\Type\Mixed'),
      array('null', 'Typhoon\Type\Null'),
      array('object', 'Typhoon\Type\Object'),
      array('string', 'Typhoon\Type\String'),
    );
  }

  /**
   * @return array
   */
  public function unexpectedArgumentData()
  {
    return array(
      array('offsetExists', array(0)),                     // #0: non-string alias
      array('offsetSet', array(null, $this->_typeName)),   // #1: null alias
      array('offsetSet', array(0, $this->_typeName)),      // #2: non-string alias
      array('offsetSet', array('foo', null)),              // #3: non-string type
      array('offsetGet', array(0)),                        // #4: non-string alias
    );
  }

  public function setUp()
  {
    $this->_registry = new TypeRegistry;
    $this->_typeName = 'foo';
  }

  /**
   * @covers Typhoon\TypeRegistry::__construct
   */
  public function testConstructor()
  {
    $registry = $this->getMock('Typhoon\TypeRegistry', array('registerDefaults'), array(), '', false);
    $registry
      ->expects($this->once())
      ->method('registerDefaults')
    ;

    $registry->__construct();
  }

  /**
   * @covers Typhoon\TypeRegistry::registerDefaults
   * @dataProvider defaultTypes
   */
  public function testRegisterDefaults($alias, $type)
  {
    $registry = $this->getMock('Typhoon\TypeRegistry', array('__construct'), array(), '', false);

    $caught = false;
    try
    {
      $registry->alias($type);
    }
    catch (UnregisteredType $e)
    {
      $caught = true;
    }
    $this->assertTrue($caught);

    $caught = false;
    try
    {
      $registry[$alias];
    }
    catch (UnregisteredTypeAlias $e)
    {
      $caught = true;
    }
    $this->assertTrue($caught);

    $registry->registerDefaults();
    $registry->alias($type);
    $registry[$alias];
  }

  /**
   * @covers Typhoon\TypeRegistry::alias
   */
  public function testAlias()
  {
    $type_1 = $this->getMockForAbstractClass('Typhoon\Type', array(), 'TypeMock1');
    $type_2 = $this->getMockForAbstractClass('Typhoon\Type', array(), 'TypeMock2');
    $typeName_1 = get_class($type_1);
    $typeName_2 = get_class($type_2);
    $alias_1 = 'foo';
    $alias_2 = 'bar';
    $this->_registry[$alias_1] = $typeName_1;

    $this->assertEquals($alias_1, $this->_registry->alias($typeName_1));
    $this->assertEquals($alias_1, $this->_registry->alias($type_1));

    $this->_registry[$alias_2] = $typeName_1;

    $this->assertEquals($alias_1, $this->_registry->alias($typeName_1));
    $this->assertEquals($alias_1, $this->_registry->alias($type_1));

    $this->_registry[$alias_1] = $typeName_2;

    $this->assertEquals($alias_1, $this->_registry->alias($typeName_2));
    $this->assertEquals($alias_1, $this->_registry->alias($type_2));

    $this->assertEquals($alias_2, $this->_registry->alias($typeName_1));
    $this->assertEquals($alias_2, $this->_registry->alias($type_1));
  }

  /**
   * @covers Typhoon\TypeRegistry::alias
   */
  public function testAliasFailure()
  {
    $this->setExpectedException(__NAMESPACE__.'\TypeRegistry\Exception\UnregisteredType');
    $this->_registry->alias($this->_typeName);
  }

  /**
   * @covers Typhoon\TypeRegistry::offsetExists
   * @covers Typhoon\TypeRegistry::offsetSet
   * @covers Typhoon\TypeRegistry::offsetGet
   */
  public function testArrayAccess()
  {
    $this->assertInstanceOf('ArrayAccess', $this->_registry);

    $this->assertFalse(isset($this->_registry['foo']));

    $this->_registry['Foo'] = $this->_typeName;

    $this->assertTrue(isset($this->_registry['foo']));
    $this->assertEquals($this->_typeName, $this->_registry['foo']);

    $this->_registry['bar'] = $this->_typeName;

    $this->assertTrue(isset($this->_registry['BAR']));
    $this->assertEquals($this->_typeName, $this->_registry['BAR']);

    $this->_registry['bar'] = 'bar';

    $this->assertEquals('bar', $this->_registry['bar']);
  }

  /**
   * @covers Typhoon\TypeRegistry::offsetGet
   */
  public function testOffsetGetFailure()
  {
    $this->setExpectedException(__NAMESPACE__.'\TypeRegistry\Exception\UnregisteredTypeAlias');
    $this->_registry['foo'];
  }

  /**
   * @covers Typhoon\TypeRegistry::offsetUnset
   */
  public function testOffsetUnsetFailure()
  {
    $this->setExpectedException(__NAMESPACE__.'\Exception\NotImplemented');
    unset($this->_registry[0]);
  }

  /**
   * @covers Typhoon\TypeRegistry::offsetExists
   * @covers Typhoon\TypeRegistry::offsetSet
   * @covers Typhoon\TypeRegistry::offsetGet
   * @dataProvider unexpectedArgumentData
   */
  public function testUnexpectedArgumentFailure($method, array $arguments)
  {
    $this->setExpectedException(__NAMESPACE__.'\ParameterList\Exception\UnexpectedArgument');
    call_user_func_array(array($this->_registry, $method), $arguments);
  }

  /**
   * @var TypeRegistry
   */
  protected $_registry;

  /**
   * @var string
   */
  protected $_typeName;
}