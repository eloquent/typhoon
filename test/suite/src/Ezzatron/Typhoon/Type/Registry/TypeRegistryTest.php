<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ezzatron\Typhoon\Type\Registry;

use Phake;

class TypeRegistryTest extends \Ezzatron\Typhoon\Test\TestCase
{
  /**
   * @return array
   */
  public function defaultTypes()
  {
    return array(
      array('array', 'Ezzatron\Typhoon\Type\ArrayType'),
      array('boolean', 'Ezzatron\Typhoon\Type\BooleanType'),
      array('callback', 'Ezzatron\Typhoon\Type\CallbackType'),
      array('float', 'Ezzatron\Typhoon\Type\FloatType'),
      array('integer', 'Ezzatron\Typhoon\Type\IntegerType'),
      array('mixed', 'Ezzatron\Typhoon\Type\MixedType'),
      array('null', 'Ezzatron\Typhoon\Type\NullType'),
      array('object', 'Ezzatron\Typhoon\Type\ObjectType'),
      array('resource', 'Ezzatron\Typhoon\Type\ResourceType'),
      array('string', 'Ezzatron\Typhoon\Type\StringType'),
      array('traversable', 'Ezzatron\Typhoon\Type\TraversableType'),

      array('bool', 'Ezzatron\Typhoon\Type\BooleanType', true),
      array('callable', 'Ezzatron\Typhoon\Type\CallbackType', true),
      array('double', 'Ezzatron\Typhoon\Type\FloatType', true),
      array('int', 'Ezzatron\Typhoon\Type\IntegerType', true),
      array('long', 'Ezzatron\Typhoon\Type\IntegerType', true),
      array('real', 'Ezzatron\Typhoon\Type\FloatType', true),
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
   * @covers Ezzatron\Typhoon\Type\Registry\TypeRegistry::__construct
   */
  public function testConstructor()
  {
    $registry = Phake::partialMock(__NAMESPACE__.'\TypeRegistry');
    
    Phake::verify($registry)->registerDefaults();
  
    $this->assertTrue(true);
  }

  /**
   * @covers Ezzatron\Typhoon\Type\Registry\TypeRegistry::registerDefaults
   * @dataProvider defaultTypes
   */
  public function testRegisterDefaults($alias, $type, $is_alias = null)
  {
    if (null === $is_alias)
    {
      $is_alias = false;
    }

    $registry = Phake::mock(__NAMESPACE__.'\TypeRegistry', Phake::ifUnstubbed()->thenCallParent());

    $caught = false;
    try
    {
      $registry->alias($type);
    }
    catch (Exception\UnregisteredTypeException $e)
    {
      $caught = true;
    }
    $this->assertTrue($caught);

    $caught = false;
    try
    {
      $registry[$alias];
    }
    catch (Exception\UnregisteredTypeAliasException $e)
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
   * @covers Ezzatron\Typhoon\Type\Registry\TypeRegistry::alias
   * @covers Ezzatron\Typhoon\Type\Registry\TypeRegistry::indexAliases
   */
  public function testAlias()
  {
    $type_1 = Phake::mock('Ezzatron\Typhoon\Type\Type');
    $type_2 = Phake::mock('Ezzatron\Typhoon\Type\Type');

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
   * @covers Ezzatron\Typhoon\Type\Registry\TypeRegistry::alias
   */
  public function testAliasFailure()
  {
    $this->setExpectedException(__NAMESPACE__.'\Exception\UnregisteredTypeException');
    $this->_registry->alias($this->_typeName);
  }

  /**
   * @covers Ezzatron\Typhoon\Type\Registry\TypeRegistry::offsetExists
   * @covers Ezzatron\Typhoon\Type\Registry\TypeRegistry::offsetSet
   * @covers Ezzatron\Typhoon\Type\Registry\TypeRegistry::offsetGet
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
   * @covers Ezzatron\Typhoon\Type\Registry\TypeRegistry::offsetGet
   */
  public function testOffsetGetFailure()
  {
    $this->setExpectedException(__NAMESPACE__.'\Exception\UnregisteredTypeAliasException');
    $this->_registry['foo'];
  }

  /**
   * @covers Ezzatron\Typhoon\Type\Registry\TypeRegistry::offsetUnset
   */
  public function testOffsetUnsetFailure()
  {
    $this->setExpectedException('Ezzatron\Typhoon\Exception\NotImplementedException');
    unset($this->_registry[0]);
  }

  /**
   * @covers Ezzatron\Typhoon\Type\Registry\TypeRegistry::offsetExists
   * @covers Ezzatron\Typhoon\Type\Registry\TypeRegistry::offsetSet
   * @covers Ezzatron\Typhoon\Type\Registry\TypeRegistry::offsetGet
   * @dataProvider unexpectedArgumentData
   */
  public function testUnexpectedArgumentFailure($method, array $arguments)
  {
    $this->setExpectedException('Ezzatron\Typhoon\Assertion\Exception\UnexpectedArgumentException');
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