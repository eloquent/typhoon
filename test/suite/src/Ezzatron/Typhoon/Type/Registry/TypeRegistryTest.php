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

use Ezzatron\Typhoon\Type\ArrayType;
use Ezzatron\Typhoon\Type\BooleanType;
use Ezzatron\Typhoon\Type\CallbackType;
use Ezzatron\Typhoon\Type\FloatType;
use Ezzatron\Typhoon\Type\IntegerType;
use Ezzatron\Typhoon\Type\MixedType;
use Ezzatron\Typhoon\Type\NullType;
use Ezzatron\Typhoon\Type\ObjectType;
use Ezzatron\Typhoon\Type\ResourceType;
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
    return array(
      array('array', new ArrayType),
      array('boolean', new BooleanType),
      array('callback', new CallbackType),
      array('float', new FloatType),
      array('integer', new IntegerType),
      array('mixed', new MixedType),
      array('null', new NullType),
      array('object', new ObjectType),
      array('resource', new ResourceType),
      array('string', new StringType),
      array('traversable', new TraversableType),

      array('bool', new BooleanType, true),
      array('callable', new CallbackType, true),
      array('double', new FloatType, true),
      array('int', new IntegerType, true),
      array('long', new IntegerType, true),
      array('real', new FloatType, true),
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
  public function testRegisterDefaults($alias, Type $type, $is_alias = null)
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
   * @covers Ezzatron\Typhoon\Type\Registry\TypeRegistry::aliases
   */
  public function testAlias()
  {
    $type_1 = Phake::mock('Ezzatron\Typhoon\Type\Type');
    $type_2 = Phake::mock('Ezzatron\Typhoon\Type\Type');

    $typeName_1 = get_class($type_1);
    $typeName_2 = get_class($type_2);
    $alias_1 = 'foo';
    $alias_2 = 'bar';
    $this->_registry[$alias_1] = $type_1;

    $this->assertEquals($alias_1, $this->_registry->alias($typeName_1));
    $this->assertEquals($alias_1, $this->_registry->alias($type_1));

    $this->_registry[$alias_2] = $type_1;

    $this->assertEquals($alias_1, $this->_registry->alias($typeName_1));
    $this->assertEquals($alias_1, $this->_registry->alias($type_1));

    $this->_registry[$alias_1] = $type_2;

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
   * @covers Ezzatron\Typhoon\Type\Registry\TypeRegistry::set
   * @covers Ezzatron\Typhoon\Type\Registry\TypeRegistry::get
   * @covers Ezzatron\Typhoon\Type\Registry\TypeRegistry::remove
   * @covers Ezzatron\Typhoon\Type\Registry\TypeRegistry::normaliseKey
   */
  public function testRegistry()
  {
    $this->assertInstanceOf('ArrayAccess', $this->_registry);

    $this->assertFalse(isset($this->_registry['foo']));
    $this->assertNull($this->_registry->get('foo', NULL));

    $this->_registry['Foo'] = $this->_type;

    $this->assertTrue(isset($this->_registry['foo']));
    $this->assertEquals($this->_type, $this->_registry['foo']);
    $this->assertNotSame($this->_type, $this->_registry['foo']);

    $this->_registry['bar'] = $this->_type;

    $this->assertTrue(isset($this->_registry['BAR']));
    $this->assertTrue(isset($this->_registry['bar']));
    $this->assertEquals($this->_type, $this->_registry['BAR']);
    $this->assertEquals($this->_type, $this->_registry['bar']);

    unset($this->_registry['FOO']);

    $this->assertFalse(isset($this->_registry['foo']));
  }

  /**
   * @covers Ezzatron\Typhoon\Type\Registry\TypeRegistry::assertKeyExists
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
   */
  public function testUnexpectedArgumentFailure($method, array $arguments)
  {
    $this->setExpectedException('Ezzatron\Typhoon\Assertion\Exception\UnexpectedTypeException');
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