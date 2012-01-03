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

use Ezzatron\Typhoon\Primitive\String;
use Ezzatron\Typhoon\Test\Fixture\ConcreteBaseType;
use Ezzatron\Typhoon\Type\ArrayType;
use Ezzatron\Typhoon\Type\BooleanType;
use Ezzatron\Typhoon\Type\CallbackType;
use Ezzatron\Typhoon\Type\CallbackWrapperType;
use Ezzatron\Typhoon\Type\ClassType;
use Ezzatron\Typhoon\Type\DirectoryType;
use Ezzatron\Typhoon\Type\FileType;
use Ezzatron\Typhoon\Type\FloatType;
use Ezzatron\Typhoon\Type\IntegerType;
use Ezzatron\Typhoon\Type\IntegerableType;
use Ezzatron\Typhoon\Type\KeyType;
use Ezzatron\Typhoon\Type\MixedType;
use Ezzatron\Typhoon\Type\NullType;
use Ezzatron\Typhoon\Type\NumberType;
use Ezzatron\Typhoon\Type\NumericType;
use Ezzatron\Typhoon\Type\ObjectType;
use Ezzatron\Typhoon\Type\ParameterType;
use Ezzatron\Typhoon\Type\ResourceType;
use Ezzatron\Typhoon\Type\ScalarType;
use Ezzatron\Typhoon\Type\StreamType;
use Ezzatron\Typhoon\Type\StringableType;
use Ezzatron\Typhoon\Type\StringType;
use Ezzatron\Typhoon\Type\TraversableType;
use Ezzatron\Typhoon\Type\Type;
use Ezzatron\Typhoon\Type\TypeType;
use Phake;

class TypeRegistryTest extends \Ezzatron\Typhoon\Test\TestCase
{
  /**
   * @return array
   */
  public function defaultTypes()
  {
    $namespace = 'Ezzatron\Typhoon\Type';
    
    return array(
      array('array', $namespace.'\ArrayType'),
      array('boolean', $namespace.'\BooleanType'),
      array('callback', $namespace.'\CallbackType'),
      array('callback_wrapper', $namespace.'\CallbackWrapperType'),
      array('class', $namespace.'\ClassType'),
      array('directory', $namespace.'\DirectoryType'),
      array('file', $namespace.'\FileType'),
      array('float', $namespace.'\FloatType'),
      array('integer', $namespace.'\IntegerType'),
      array('integerable', $namespace.'\IntegerableType'),
      array('key', $namespace.'\KeyType'),
      array('mixed', $namespace.'\MixedType'),
      array('null', $namespace.'\NullType'),
      array('number', $namespace.'\NumberType'),
      array('numeric', $namespace.'\NumericType'),
      array('object', $namespace.'\ObjectType'),
      array('resource', $namespace.'\ResourceType'),
      array('scalar', $namespace.'\ScalarType'),
      array('socket', $namespace.'\SocketType'),
      array('stream', $namespace.'\StreamType'),
      array('string', $namespace.'\StringType'),
      array('stringable', $namespace.'\StringableType'),
      array('traversable', $namespace.'\TraversableType'),
      array('typhoon_parameter', $namespace.'\ParameterType'),
      array('typhoon_type', $namespace.'\TypeType'),

      array('bool', $namespace.'\BooleanType', true),
      array('callable', $namespace.'\CallbackType', true),
      array('double', $namespace.'\FloatType', true),
      array('floatable', $namespace.'\NumericType', true),
      array('int', $namespace.'\IntegerType', true),
      array('keyable', $namespace.'\KeyType', true),
      array('long', $namespace.'\IntegerType', true),
      array('real', $namespace.'\FloatType', true),
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
    $this->_type = Phake::mock('Ezzatron\Typhoon\Type\Type');
    $this->_typeName = get_class($this->_type);
  }

  /**
   * @covers Ezzatron\Typhoon\Type\Registry\TypeRegistry::__construct
   * @group collection
   * @group type
   * @group type-registry
   * @group core
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
   * @group collection
   * @group type
   * @group type-registry
   * @group core
   */
  public function testRegisterDefaults($alias, $class, $is_alias = null)
  {
    if (null === $is_alias)
    {
      $is_alias = false;
    }

    $registry = Phake::mock(__NAMESPACE__.'\TypeRegistry', Phake::ifUnstubbed()->thenCallParent());

    $caught = false;
    try
    {
      $registry->alias(new String($class));
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
      $this->assertEquals($alias, $registry->alias(new String($class)));
    }

    $this->assertEquals($class, $registry[$alias]);
  }

  /**
   * @covers Ezzatron\Typhoon\Type\Registry\TypeRegistry::alias
   * @covers Ezzatron\Typhoon\Type\Registry\TypeRegistry::aliasByType
   * @covers Ezzatron\Typhoon\Type\Registry\TypeRegistry::aliases
   * @group collection
   * @group type
   * @group type-registry
   * @group core
   */
  public function testAlias()
  {
    $type_1 = Phake::mock('Ezzatron\Typhoon\Type\Type');
    $type_2 = new ConcreteBaseType;

    $typeName_1 = get_class($type_1);
    $typeName_2 = get_class($type_2);
    $alias_1 = 'foo';
    $alias_2 = 'bar';
    $this->_registry[$alias_1] = $typeName_1;
    
    $this->assertNotEquals($typeName_1, $typeName_2);

    $this->assertEquals($alias_1, $this->_registry->alias(new String($typeName_1)));
    $this->assertEquals($alias_1, $this->_registry->aliasByType($type_1));

    $this->_registry[$alias_2] = $typeName_1;

    $this->assertEquals($alias_1, $this->_registry->alias(new String($typeName_1)));
    $this->assertEquals($alias_1, $this->_registry->aliasByType($type_1));

    $this->_registry[$alias_1] = $typeName_2;

    $this->assertEquals($alias_1, $this->_registry->alias(new String($typeName_2)));
    $this->assertEquals($alias_1, $this->_registry->aliasByType($type_2));

    $this->assertEquals($alias_2, $this->_registry->alias(new String($typeName_1)));
    $this->assertEquals($alias_2, $this->_registry->aliasByType($type_1));

    $type = new ObjectType(array(
      ObjectType::ATTRIBUTE_INSTANCE_OF => 'foo',
    ));

    $this->assertEquals(TypeRegistry::TYPE_OBJECT, $this->_registry->aliasByType($type));
  }

  /**
   * @covers Ezzatron\Typhoon\Type\Registry\TypeRegistry::alias
   * @group collection
   * @group type
   * @group type-registry
   * @group core
   */
  public function testAliasFailure()
  {
    $this->setExpectedException(__NAMESPACE__.'\Exception\UnregisteredTypeException');
    $this->_registry->alias(new String($this->_typeName));
  }

  /**
   * @covers Ezzatron\Typhoon\Type\Registry\TypeRegistry::isRegistered
   * @covers Ezzatron\Typhoon\Type\Registry\TypeRegistry::set
   * @covers Ezzatron\Typhoon\Type\Registry\TypeRegistry::get
   * @covers Ezzatron\Typhoon\Type\Registry\TypeRegistry::remove
   * @covers Ezzatron\Typhoon\Type\Registry\TypeRegistry::normaliseKey
   * @group collection
   * @group type
   * @group type-registry
   * @group core
   */
  public function testRegistry()
  {
    $this->assertInstanceOf('ArrayAccess', $this->_registry);

    $this->assertFalse($this->_registry->isRegistered(new String($this->_typeName)));
    $this->assertFalse(isset($this->_registry['foo']));
    $this->assertNull($this->_registry->get('foo', NULL));

    $this->_registry['Foo'] = $this->_typeName;

    $this->assertTrue($this->_registry->isRegistered(new String($this->_typeName)));
    $this->assertTrue(isset($this->_registry['foo']));
    $this->assertEquals($this->_typeName, $this->_registry['foo']);

    $this->_registry['bar'] = $this->_typeName;

    $this->assertTrue(isset($this->_registry['BAR']));
    $this->assertTrue(isset($this->_registry['bar']));
    $this->assertEquals($this->_typeName, $this->_registry['BAR']);
    $this->assertEquals($this->_typeName, $this->_registry['bar']);

    unset($this->_registry['FOO']);

    $this->assertFalse(isset($this->_registry['foo']));
  }

  /**
   * @covers Ezzatron\Typhoon\Type\Registry\TypeRegistry::assertKeyExists
   * @group collection
   * @group type
   * @group type-registry
   * @group core
   */
  public function testOffsetGetFailure()
  {
    $this->setExpectedException(__NAMESPACE__.'\Exception\UnregisteredTypeAliasException');
    $this->_registry['foo'];
  }

  /**
   * @covers Ezzatron\Typhoon\Type\Registry\TypeRegistry::keyType
   * @covers Ezzatron\Typhoon\Type\Registry\TypeRegistry::allowEmptyKeyForSet
   * @covers Ezzatron\Typhoon\Type\Registry\TypeRegistry::valueType
   * @dataProvider unexpectedArgumentData
   * @group collection
   * @group type
   * @group type-registry
   * @group core
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
   * @var Type
   */
  protected $_type;

  /**
   * @var string
   */
  protected $_typeName;
}
