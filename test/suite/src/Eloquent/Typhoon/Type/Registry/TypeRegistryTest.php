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
      array(IntrinsicTypeName::NAME_ARRAY()->_value(), $namespace.'\ArrayType'),
      array(IntrinsicTypeName::NAME_BOOLEAN()->_value(), $namespace.'\BooleanType'),
      array(IntrinsicTypeName::NAME_CALLBACK()->_value(), $namespace.'\CallbackType'),
      array(IntrinsicTypeName::NAME_CALLBACK_WRAPPER()->_value(), $namespace.'\CallbackWrapperType'),
      array(IntrinsicTypeName::NAME_CHARACTER()->_value(), $namespace.'\CharacterType'),
      array(IntrinsicTypeName::NAME_CLASS_NAME()->_value(), $namespace.'\ClassNameType'),
      array(IntrinsicTypeName::NAME_DIRECTORY()->_value(), $namespace.'\DirectoryType'),
      array(IntrinsicTypeName::NAME_FILE()->_value(), $namespace.'\FileType'),
      array(IntrinsicTypeName::NAME_FLOAT()->_value(), $namespace.'\FloatType'),
      array(IntrinsicTypeName::NAME_INTEGER()->_value(), $namespace.'\IntegerType'),
      array(IntrinsicTypeName::NAME_INTEGERABLE()->_value(), $namespace.'\IntegerableType'),
      array(IntrinsicTypeName::NAME_INTERFACE_NAME()->_value(), $namespace.'\InterfaceNameType'),
      array(IntrinsicTypeName::NAME_KEY()->_value(), $namespace.'\KeyType'),
      array(IntrinsicTypeName::NAME_MIXED()->_value(), $namespace.'\MixedType'),
      array(IntrinsicTypeName::NAME_NULL()->_value(), $namespace.'\NullType'),
      array(IntrinsicTypeName::NAME_NUMBER()->_value(), $namespace.'\NumberType'),
      array(IntrinsicTypeName::NAME_NUMERIC()->_value(), $namespace.'\NumericType'),
      array(IntrinsicTypeName::NAME_OBJECT()->_value(), $namespace.'\ObjectType'),
      array(IntrinsicTypeName::NAME_RESOURCE()->_value(), $namespace.'\ResourceType'),
      array(IntrinsicTypeName::NAME_SCALAR()->_value(), $namespace.'\ScalarType'),
      array(IntrinsicTypeName::NAME_SOCKET()->_value(), $namespace.'\SocketType'),
      array(IntrinsicTypeName::NAME_STREAM()->_value(), $namespace.'\StreamType'),
      array(IntrinsicTypeName::NAME_STRING()->_value(), $namespace.'\StringType'),
      array(IntrinsicTypeName::NAME_STRINGABLE()->_value(), $namespace.'\StringableType'),
      array(IntrinsicTypeName::NAME_TRAVERSABLE()->_value(), $namespace.'\TraversableType'),

      array(IntrinsicTypeAlias::ALIAS_BOOL()->_value(), $namespace.'\BooleanType', true),
      array(IntrinsicTypeAlias::ALIAS_CALLABLE()->_value(), $namespace.'\CallbackType', true),
      array(IntrinsicTypeAlias::ALIAS_DOUBLE()->_value(), $namespace.'\FloatType', true),
      array(IntrinsicTypeAlias::ALIAS_FLOATABLE()->_value(), $namespace.'\NumericType', true),
      array(IntrinsicTypeAlias::ALIAS_INT()->_value(), $namespace.'\IntegerType', true),
      array(IntrinsicTypeAlias::ALIAS_KEYABLE()->_value(), $namespace.'\ScalarType', true),
      array(IntrinsicTypeAlias::ALIAS_LONG()->_value(), $namespace.'\IntegerType', true),
      array(IntrinsicTypeAlias::ALIAS_REAL()->_value(), $namespace.'\FloatType', true),

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
      array('offsetExists', array(0)),                     // #0: non-string type name
      array('offsetSet', array(null, $this->_typeName)),   // #1: null type name
      array('offsetSet', array(0, $this->_typeName)),      // #2: non-string type name
      array('offsetSet', array('foo.bar', null)),          // #3: non-string type
      array('offsetGet', array(0)),                        // #4: non-string type name
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
   * @covers Eloquent\Typhoon\Type\Registry\TypeRegistry::setIntrinsic
   * @dataProvider defaultTypes
   * @group collection
   * @group type
   * @group type-registry
   * @group core
   */
  public function testRegisterDefaults($typeName, $class, $is_alias = null)
  {
    if (null === $is_alias)
    {
      $is_alias = false;
    }

    $registry = Phake::mock(__NAMESPACE__.'\TypeRegistry', Phake::ifUnstubbed()->thenCallParent());

    $caught = false;
    try
    {
      $registry->typeNameByClass(new String($class));
    }
    catch (Exception\UnregisteredTypeException $e)
    {
      $caught = true;
    }
    $this->assertTrue($caught);

    $caught = false;
    try
    {
      $registry[$typeName];
    }
    catch (Exception\UnregisteredTypeNameException $e)
    {
      $caught = true;
    }
    $this->assertTrue($caught);

    $registry->registerDefaults();

    if (!$is_alias)
    {
      $this->assertEquals($typeName, $registry->typeNameByClass(new String($class)));
    }

    $this->assertEquals($class, $registry[$typeName]);
  }

  /**
   * @covers Eloquent\Typhoon\Type\Registry\TypeRegistry::typeNameByClass
   * @covers Eloquent\Typhoon\Type\Registry\TypeRegistry::typeNameByType
   * @covers Eloquent\Typhoon\Type\Registry\TypeRegistry::typeNames
   * @group collection
   * @group type
   * @group type-registry
   * @group core
   */
  public function testTypeNameMethods()
  {
    $type_1 = Phake::mock('Eloquent\Typhoon\Type\NamedType');
    $type_2 = new ConcreteType;

    $typeClass_1 = get_class($type_1);
    $typeClass_2 = get_class($type_2);
    $typeName_1 = 'foo.bar';
    $typeName_2 = 'baz.qux';
    $this->_registry[$typeName_1] = $typeClass_1;

    $this->assertEquals($typeName_1, $this->_registry->typeNameByClass(new String($typeClass_1)));
    $this->assertEquals($typeName_1, $this->_registry->typeNameByType($type_1));

    $this->_registry[$typeName_2] = $typeClass_1;

    $this->assertEquals($typeName_1, $this->_registry->typeNameByClass(new String($typeClass_1)));
    $this->assertEquals($typeName_1, $this->_registry->typeNameByType($type_1));

    $this->_registry[$typeName_1] = $typeClass_2;

    $this->assertEquals($typeName_1, $this->_registry->typeNameByClass(new String($typeClass_2)));
    $this->assertEquals($typeName_1, $this->_registry->typeNameByType($type_2));

    $this->assertEquals($typeName_2, $this->_registry->typeNameByClass(new String($typeClass_1)));
    $this->assertEquals($typeName_2, $this->_registry->typeNameByType($type_1));

    $type = new ObjectType(array(
      ObjectType::ATTRIBUTE_INSTANCE_OF => 'foo',
    ));

    $this->assertEquals(IntrinsicTypeName::NAME_OBJECT()->_value(), $this->_registry->typeNameByType($type));
  }

  /**
   * @covers Eloquent\Typhoon\Type\Registry\TypeRegistry::typeNameByClass
   * @group collection
   * @group type
   * @group type-registry
   * @group core
   */
  public function testTypeNameByClassFailure()
  {
    $this->setExpectedException(__NAMESPACE__.'\Exception\UnregisteredTypeException');
    $this->_registry->typeNameByClass(new String($this->_typeName));
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
    $this->assertFalse(isset($this->_registry['foo.bar']));
    $this->assertNull($this->_registry->get('foo.bar', NULL));

    $this->_registry['Foo.Bar'] = $this->_typeName;

    $this->assertTrue($this->_registry->isRegistered(new String($this->_typeName)));
    $this->assertTrue(isset($this->_registry['foo.bar']));
    $this->assertEquals($this->_typeName, $this->_registry['foo.bar']);

    $this->_registry['baz.qux'] = $this->_typeName;

    $this->assertTrue(isset($this->_registry['BAZ.QUX']));
    $this->assertTrue(isset($this->_registry['baz.qux']));
    $this->assertEquals($this->_typeName, $this->_registry['BAZ.QUX']);
    $this->assertEquals($this->_typeName, $this->_registry['baz.qux']);

    unset($this->_registry['FOO.BAR']);

    $this->assertFalse(isset($this->_registry['foo.bar']));
  }

  /**
   * @covers Eloquent\Typhoon\Type\Registry\TypeRegistry::assertKeyExists
   * @covers Eloquent\Typhoon\Type\Registry\TypeRegistry::assertTypeNameNamespace
   * @group collection
   * @group type
   * @group type-registry
   * @group core
   */
  public function testSetFailureNoNamespace()
  {
    $this->setExpectedException(__NAMESPACE__.'\Exception\InvalidTypeNameNamespaceException');
    $this->_registry['foo'] = 'bar';
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
    $this->setExpectedException(__NAMESPACE__.'\Exception\UnregisteredTypeNameException');
    $this->_registry['foo.bar'];
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
