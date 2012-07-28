<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Collection;

use Phake;
use Eloquent\Typhoon\Type\NullType;

class CollectionTest extends \Eloquent\Typhoon\Test\TestCase
{
  /**
   * @return array
   */
  public function undefinedKeyTriggers()
  {
    return array(
      array('get'),
      array('remove'),
      array('offsetGet'),
    );
  }

  protected function setUp()
  {
    $this->_collection = new Collection;
  }

  /**
   * @covers Eloquent\Typhoon\Collection\Collection
   * @group collection
   * @group core
   */
  public function testCollection()
  {
    $this->assertEquals(array(), $this->_collection->values());
    $this->assertFalse($this->_collection->exists('foo'));
    $this->assertFalse(isset($this->_collection['foo']));
    $this->assertEquals(0, count($this->_collection));
    $this->assertTrue($this->_collection->isEmpty());

    $this->_collection->set('foo', 'bar');
    $this->_collection['baz'] = 'qux';
    $this->_collection[] = 'doom';

    $this->assertEquals(array(0 => 'doom', 'foo' => 'bar', 'baz' => 'qux'), $this->_collection->values());
    $this->assertTrue($this->_collection->exists('foo'));
    $this->assertTrue(isset($this->_collection['foo']));
    $this->assertTrue($this->_collection->exists('baz'));
    $this->assertTrue(isset($this->_collection['baz']));
    $this->assertTrue($this->_collection->exists(0));
    $this->assertTrue(isset($this->_collection[0]));
    $this->assertFalse($this->_collection->exists('bar'));
    $this->assertFalse(isset($this->_collection['bar']));
    $this->assertEquals('bar', $this->_collection->get('foo'));
    $this->assertEquals('bar', $this->_collection['foo']);
    $this->assertEquals('qux', $this->_collection->get('baz'));
    $this->assertEquals('qux', $this->_collection['baz']);
    $this->assertEquals('doom', $this->_collection->get(0));
    $this->assertEquals('doom', $this->_collection[0]);
    $this->assertNull($this->_collection->get('bar', null));
    $this->assertEquals('splat', $this->_collection->get('bar', 'splat'));
    $this->assertEquals(3, count($this->_collection));
    $this->assertFalse($this->_collection->isEmpty());

    $this->_collection->remove('foo');
    unset($this->_collection['baz']);
    unset($this->_collection[0]);

    $this->assertEquals(array(), $this->_collection->values());
    $this->assertFalse($this->_collection->exists('foo'));
    $this->assertFalse(isset($this->_collection['foo']));
    $this->assertEquals(0, count($this->_collection));
    $this->assertTrue($this->_collection->isEmpty());

    $array = array(0 => 'doom', 'foo' => 'bar', 'baz' => 'qux');
    $this->_collection = new Collection($array);

    $this->assertEquals(array(0 => 'doom', 'foo' => 'bar', 'baz' => 'qux'), $this->_collection->values());
  }

  /**
   * @covers Eloquent\Typhoon\Collection\Collection::keySetType
   * @group collection
   * @group core
   */
  public function testKeySetType()
  {
    $this->_collection = Phake::partialMock(__NAMESPACE__.'\Collection');
    Phake::when($this->_collection)->allowEmptyKeyForSet()->thenReturn(false);

    $this->setExpectedException('Eloquent\Typhoon\Assertion\Exception\UnexpectedArgumentException');
    $this->_collection[] = 'foo';
  }

  /**
   * @covers Eloquent\Typhoon\Collection\Collection
   * @dataProvider undefinedKeyTriggers
   * @group collection
   * @group core
   */
  public function testUndefinedKeyFailure($method)
  {
    $this->setExpectedException(__NAMESPACE__.'\Exception\UndefinedKeyException');
    $this->_collection->$method('foo');
  }

  /**
   * @covers Eloquent\Typhoon\Collection\Collection
   * @group collection
   * @group core
   */
  public function testInvalidKeyTypeFailure()
  {
    $this->setExpectedException('Eloquent\Typhoon\Assertion\Exception\UnexpectedArgumentException');
    $this->_collection[.1] = 'foo';
  }

  /**
   * @covers Eloquent\Typhoon\Collection\Collection
   * @group collection
   * @group core
   */
  public function testInvalidValueTypeFailure()
  {
    $collection = Phake::partialMock(__NAMESPACE__.'\Collection');
    Phake::when($collection)->valueType('foo')->thenReturn(new NullType);

    $this->setExpectedException('Eloquent\Typhoon\Assertion\Exception\UnexpectedArgumentException');
    $collection['foo'] = 'bar';
  }

  /**
   * @covers Eloquent\Typhoon\Collection\Collection
   * @group collection
   * @group core
   */
  public function testImplements()
  {
    $this->assertInstanceOf('ArrayAccess', $this->_collection);
    $this->assertInstanceOf('Countable', $this->_collection);
    $this->assertInstanceOf('Traversable', $this->_collection);
  }
  
  /**
   * @var Collection
   */
  protected $_collection;
}
