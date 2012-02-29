<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Type\Registry;

use Eloquent\Typhoon\Primitive\String;
use Eloquent\Typhoon\Test\Fixture\ConcreteBaseType;
use Eloquent\Typhoon\Type\ArrayType;
use Eloquent\Typhoon\Type\BooleanType;
use Eloquent\Typhoon\Type\CallbackType;
use Eloquent\Typhoon\Type\CallbackWrapperType;
use Eloquent\Typhoon\Type\ClassType;
use Eloquent\Typhoon\Type\DirectoryType;
use Eloquent\Typhoon\Type\FileType;
use Eloquent\Typhoon\Type\FloatType;
use Eloquent\Typhoon\Type\IntegerType;
use Eloquent\Typhoon\Type\IntegerableType;
use Eloquent\Typhoon\Type\KeyType;
use Eloquent\Typhoon\Type\MixedType;
use Eloquent\Typhoon\Type\NullType;
use Eloquent\Typhoon\Type\NumberType;
use Eloquent\Typhoon\Type\NumericType;
use Eloquent\Typhoon\Type\ObjectType;
use Eloquent\Typhoon\Type\ParameterType;
use Eloquent\Typhoon\Type\ResourceType;
use Eloquent\Typhoon\Type\ScalarType;
use Eloquent\Typhoon\Type\StreamType;
use Eloquent\Typhoon\Type\StringableType;
use Eloquent\Typhoon\Type\StringType;
use Eloquent\Typhoon\Type\TraversableType;
use Eloquent\Typhoon\Type\Type;
use Eloquent\Typhoon\Type\TypeType;
use Phake;

class TypeRegistryTest extends \Eloquent\Typhoon\Test\TestCase
{
  /**
   * @return array
   */
  public function defaultTypes()
  {
    $namespace = 'Eloquent\Typhoon\Type';
    
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
    $this->_type = Phake::mock('Eloquent\Typhoon\Type\Type');
    $this->_typeName = get_class($this->_type);
  }

  /**
   * @covers Eloquent\Typhoon\Type\Registry\TypeRegistry::__construct
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
   * @covers Eloquent\Typhoon\Type\Registry\TypeRegistry::registerDefaults
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
   * @covers Eloquent\Typhoon\Type\Registry\TypeRegistry::alias
   * @covers Eloquent\Typhoon\Type\Registry\TypeRegistry::aliasByType
   * @covers Eloquent\Typhoon\Type\Registry\TypeRegistry::aliases
   * @group collection
   * @group type
   * @group type-registry
   * @group core
   */
  public function testAlias()
  {
    $type_1 = Phake::mock('Eloquent\Typhoon\Type\Type');
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
   * @covers Eloquent\Typhoon\Type\Registry\TypeRegistry::alias
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
   * @covers Eloquent\Typhoon\Type\Registry\TypeRegistry::isRegistered
   * @covers Eloquent\Typhoon\Type\Registry\TypeRegistry::set
   * @covers Eloquent\Typhoon\Type\Registry\TypeRegistry::get
   * @covers Eloquent\Typhoon\Type\Registry\TypeRegistry::remove
   * @covers Eloquent\Typhoon\Type\Registry\TypeRegistry::normaliseKey
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
   * @covers Eloquent\Typhoon\Type\Registry\TypeRegistry::assertKeyExists
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
   * @covers Eloquent\Typhoon\Type\Registry\TypeRegistry::keyType
   * @covers Eloquent\Typhoon\Type\Registry\TypeRegistry::allowEmptyKeyForSet
   * @covers Eloquent\Typhoon\Type\Registry\TypeRegistry::valueType
   * @dataProvider unexpectedArgumentData
   * @group collection
   * @group type
   * @group type-registry
   * @group core
   */
  public function testUnexpectedArgumentFailure($method, array $arguments)
  {
    $this->setExpectedException('Eloquent\Typhoon\Assertion\Exception\UnexpectedArgumentException');
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
