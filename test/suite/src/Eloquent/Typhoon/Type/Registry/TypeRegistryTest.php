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

use Eloquent\Typhax\IntrinsicType\IntrinsicTypeName;
use Eloquent\Typhax\IntrinsicType\IntrinsicTypeAlias;
use Eloquent\Typhoon\Primitive\String;
use Eloquent\Typhoon\Test\Fixture\ConcreteType;
use Eloquent\Typhoon\Type\ObjectType;
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
      array(IntrinsicTypeName::NAME_ARRAY()->value(), $namespace.'\ArrayType'),
      array(IntrinsicTypeName::NAME_BOOLEAN()->value(), $namespace.'\BooleanType'),
      array(IntrinsicTypeName::NAME_CALLBACK()->value(), $namespace.'\CallbackType'),
      array(IntrinsicTypeName::NAME_CALLBACK_WRAPPER()->value(), $namespace.'\CallbackWrapperType'),
      array(IntrinsicTypeName::NAME_CHARACTER()->value(), $namespace.'\CharacterType'),
      array(IntrinsicTypeName::NAME_CLASS_NAME()->value(), $namespace.'\ClassNameType'),
      array(IntrinsicTypeName::NAME_DIRECTORY()->value(), $namespace.'\DirectoryType'),
      array(IntrinsicTypeName::NAME_FILE()->value(), $namespace.'\FileType'),
      array(IntrinsicTypeName::NAME_FLOAT()->value(), $namespace.'\FloatType'),
      array(IntrinsicTypeName::NAME_INTEGER()->value(), $namespace.'\IntegerType'),
      array(IntrinsicTypeName::NAME_INTEGERABLE()->value(), $namespace.'\IntegerableType'),
      array(IntrinsicTypeName::NAME_INTERFACE_NAME()->value(), $namespace.'\InterfaceNameType'),
      array(IntrinsicTypeName::NAME_KEY()->value(), $namespace.'\KeyType'),
      array(IntrinsicTypeName::NAME_MIXED()->value(), $namespace.'\MixedType'),
      array(IntrinsicTypeName::NAME_NULL()->value(), $namespace.'\NullType'),
      array(IntrinsicTypeName::NAME_NUMBER()->value(), $namespace.'\NumberType'),
      array(IntrinsicTypeName::NAME_NUMERIC()->value(), $namespace.'\NumericType'),
      array(IntrinsicTypeName::NAME_OBJECT()->value(), $namespace.'\ObjectType'),
      array(IntrinsicTypeName::NAME_RESOURCE()->value(), $namespace.'\ResourceType'),
      array(IntrinsicTypeName::NAME_SCALAR()->value(), $namespace.'\ScalarType'),
      array(IntrinsicTypeName::NAME_SOCKET()->value(), $namespace.'\SocketType'),
      array(IntrinsicTypeName::NAME_STREAM()->value(), $namespace.'\StreamType'),
      array(IntrinsicTypeName::NAME_STRING()->value(), $namespace.'\StringType'),
      array(IntrinsicTypeName::NAME_STRINGABLE()->value(), $namespace.'\StringableType'),
      array(IntrinsicTypeName::NAME_TRAVERSABLE()->value(), $namespace.'\TraversableType'),

      array(IntrinsicTypeAlias::ALIAS_BOOL()->value(), $namespace.'\BooleanType', true),
      array(IntrinsicTypeAlias::ALIAS_CALLABLE()->value(), $namespace.'\CallbackType', true),
      array(IntrinsicTypeAlias::ALIAS_DOUBLE()->value(), $namespace.'\FloatType', true),
      array(IntrinsicTypeAlias::ALIAS_FLOATABLE()->value(), $namespace.'\NumericType', true),
      array(IntrinsicTypeAlias::ALIAS_INT()->value(), $namespace.'\IntegerType', true),
      array(IntrinsicTypeAlias::ALIAS_KEYABLE()->value(), $namespace.'\ScalarType', true),
      array(IntrinsicTypeAlias::ALIAS_LONG()->value(), $namespace.'\IntegerType', true),
      array(IntrinsicTypeAlias::ALIAS_REAL()->value(), $namespace.'\FloatType', true),

      array('typhoon.parameter', $namespace.'\ParameterType'),
      array('typhoon.type', $namespace.'\TypeType'),
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
    $this->_type = Phake::mock('Eloquent\Typhoon\Type\NamedType');
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
    $type_1 = Phake::mock('Eloquent\Typhoon\Type\NamedType');
    $type_2 = new ConcreteType;

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

    $this->assertEquals(IntrinsicTypeName::NAME_OBJECT()->value(), $this->_registry->aliasByType($type));
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
