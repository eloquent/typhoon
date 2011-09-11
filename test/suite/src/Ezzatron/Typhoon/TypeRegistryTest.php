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
use Ezzatron\Typhoon\TypeRegistry\Exception\UnregisteredType;
use Ezzatron\Typhoon\TypeRegistry\Exception\UnregisteredTypeAlias;

class TypeRegistryTest extends TestCase
{
  /**
   * @return array
   */
  public function defaultTypes()
  {
    return array(
      array('array', 'Ezzatron\Typhoon\Type\ArrayType'),
      array('boolean', 'Ezzatron\Typhoon\Type\Boolean'),
      array('callback', 'Ezzatron\Typhoon\Type\Callback'),
      array('float', 'Ezzatron\Typhoon\Type\Float'),
      array('integer', 'Ezzatron\Typhoon\Type\Integer'),
      array('mixed', 'Ezzatron\Typhoon\Type\Mixed'),
      array('null', 'Ezzatron\Typhoon\Type\Null'),
      array('object', 'Ezzatron\Typhoon\Type\Object'),
      array('resource', 'Ezzatron\Typhoon\Type\Resource'),
      array('string', 'Ezzatron\Typhoon\Type\String'),
      array('traversable', 'Ezzatron\Typhoon\Type\Traversable'),

      array('bool', 'Ezzatron\Typhoon\Type\Boolean', true),
      array('callable', 'Ezzatron\Typhoon\Type\Callback', true),
      array('double', 'Ezzatron\Typhoon\Type\Float', true),
      array('int', 'Ezzatron\Typhoon\Type\Integer', true),
      array('long', 'Ezzatron\Typhoon\Type\Integer', true),
      array('real', 'Ezzatron\Typhoon\Type\Float', true),
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

  protected function setUp()
  {
    parent::setUp();
    
    $this->_registry = new TypeRegistry;
    $this->_typeName = 'foo';
  }

  /**
   * @covers Ezzatron\Typhoon\TypeRegistry::__construct
   */
  public function testConstructor()
  {
    $registry = Phake::partialMock('Ezzatron\Typhoon\TypeRegistry');
    
    Phake::verify($registry)->registerDefaults();
  
    $this->assertTrue(true);
  }

  /**
   * @covers Ezzatron\Typhoon\TypeRegistry::registerDefaults
   * @dataProvider defaultTypes
   */
  public function testRegisterDefaults($alias, $type, $is_alias = null)
  {
    if (null === $is_alias)
    {
      $is_alias = false;
    }

    $registry = Phake::mock('Ezzatron\Typhoon\TypeRegistry', Phake::ifUnstubbed()->thenCallParent());

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

    if (!$is_alias)
    {
      $this->assertEquals($alias, $registry->alias($type));
    }

    $this->assertEquals($type, $registry[$alias]);
  }

  /**
   * @covers Ezzatron\Typhoon\TypeRegistry::alias
   * @covers Ezzatron\Typhoon\TypeRegistry::indexAliases
   */
  public function testAlias()
  {
    $type_1 = Phake::mock(__NAMESPACE__.'\Type');
    $type_2 = Phake::mock(__NAMESPACE__.'\Type');

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
   * @covers Ezzatron\Typhoon\TypeRegistry::alias
   */
  public function testAliasFailure()
  {
    $this->setExpectedException(__NAMESPACE__.'\TypeRegistry\Exception\UnregisteredType');
    $this->_registry->alias($this->_typeName);
  }

  /**
   * @covers Ezzatron\Typhoon\TypeRegistry::offsetExists
   * @covers Ezzatron\Typhoon\TypeRegistry::offsetSet
   * @covers Ezzatron\Typhoon\TypeRegistry::offsetGet
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
   * @covers Ezzatron\Typhoon\TypeRegistry::offsetGet
   */
  public function testOffsetGetFailure()
  {
    $this->setExpectedException(__NAMESPACE__.'\TypeRegistry\Exception\UnregisteredTypeAlias');
    $this->_registry['foo'];
  }

  /**
   * @covers Ezzatron\Typhoon\TypeRegistry::offsetUnset
   */
  public function testOffsetUnsetFailure()
  {
    $this->setExpectedException(__NAMESPACE__.'\Exception\NotImplemented');
    unset($this->_registry[0]);
  }

  /**
   * @covers Ezzatron\Typhoon\TypeRegistry::offsetExists
   * @covers Ezzatron\Typhoon\TypeRegistry::offsetSet
   * @covers Ezzatron\Typhoon\TypeRegistry::offsetGet
   * @dataProvider unexpectedArgumentData
   */
  public function testUnexpectedArgumentFailure($method, array $arguments)
  {
    $this->setExpectedException(__NAMESPACE__.'\Assertion\Exception\UnexpectedArgument');
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