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

class TypeRegistryTest extends PHPUnit_Framework_TestCase
{
  /**
   * @return array
   */
  public function unexpectedArgumentData()
  {
    return array(
      array('offsetExists', array(0)),                     // #0: non-string alias
      array('offsetSet', array(null, $this->_typeName)),  // #1: null alias
      array('offsetSet', array(0, $this->_typeName)),     // #2: non-string alias
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
   * @covers \Typhoon\TypeRegistry::alias
   */
  public function testAlias()
  {
    $type = $this->getMockForAbstractClass('\Typhoon\Type');
    $typeName = get_class($type);
    $alias = 'foo';
    $this->_registry[$alias] = $typeName;

    $this->assertEquals($alias, $this->_registry->alias($typeName));
    $this->assertEquals($alias, $this->_registry->alias($type));

    $this->_registry['bar'] = $typeName;

    $this->assertEquals($alias, $this->_registry->alias($typeName));
    $this->assertEquals($alias, $this->_registry->alias($type));
  }

  /**
   * @covers \Typhoon\TypeRegistry::alias
   */
  public function testAliasFailure()
  {
    $this->setExpectedException(__NAMESPACE__.'\TypeRegistry\Exception\UnregisteredType');
    $this->_registry->alias($this->_typeName);
  }

  /**
   * @covers \Typhoon\TypeRegistry::offsetExists
   * @covers \Typhoon\TypeRegistry::offsetSet
   * @covers \Typhoon\TypeRegistry::offsetGet
   */
  public function testArrayAccess()
  {
    $this->assertInstanceOf('\ArrayAccess', $this->_registry);

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
   * @covers \Typhoon\TypeRegistry::offsetGet
   */
  public function testOffsetGetFailure()
  {
    $this->setExpectedException(__NAMESPACE__.'\TypeRegistry\Exception\UnregisteredTypeAlias');
    $this->_registry['foo'];
  }

  /**
   * @covers \Typhoon\TypeRegistry::offsetUnset
   */
  public function testOffsetUnsetFailure()
  {
    $this->setExpectedException(__NAMESPACE__.'\Exception\NotImplemented');
    unset($this->_registry[0]);
  }

  /**
   * @covers \Typhoon\TypeRegistry::offsetExists
   * @covers \Typhoon\TypeRegistry::offsetSet
   * @covers \Typhoon\TypeRegistry::offsetGet
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