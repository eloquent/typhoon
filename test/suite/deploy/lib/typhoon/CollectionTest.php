<?php

namespace Typhoon;

use Typhoon\Test\TestCase;

class CollectionTest extends TestCase
{
  /**
   * @return array
   */
  public function undefinedKeyTriggers()
  {
    return array(
      array('offsetGet'),
      array('offsetUnset'),
    );
  }

  protected function setUp()
  {
    $this->_collection = new Collection;
  }

  /**
   * @covers Typhoon\Collection
   */
  public function testCollection()
  {
    $this->assertEquals(array(), iterator_to_array($this->_collection, true));
    $this->assertFalse(isset($this->_collection['foo']));
    $this->assertEquals(0, count($this->_collection));

    $this->_collection['foo'] = 'bar';
    $this->_collection['baz'] = 'qux';
    $this->_collection[] = 'doom';

    $this->assertEquals(array(0 => 'doom', 'foo' => 'bar', 'baz' => 'qux'), iterator_to_array($this->_collection, true));
    $this->assertTrue(isset($this->_collection['foo']));
    $this->assertTrue(isset($this->_collection['baz']));
    $this->assertTrue(isset($this->_collection[0]));
    $this->assertFalse(isset($this->_collection['bar']));
    $this->assertEquals('bar', $this->_collection['foo']);
    $this->assertEquals('qux', $this->_collection['baz']);
    $this->assertEquals('doom', $this->_collection[0]);
    $this->assertEquals(3, count($this->_collection));

    unset($this->_collection['foo']);
    unset($this->_collection['baz']);
    unset($this->_collection[0]);

    $this->assertEquals(array(), iterator_to_array($this->_collection, true));
    $this->assertFalse(isset($this->_collection['foo']));
    $this->assertEquals(0, count($this->_collection));
  }

  /**
   * @covers Typhoon\Collection
   * @dataProvider undefinedKeyTriggers
   */
  public function testUndefinedKeyFailure($method)
  {
    $this->setExpectedException('Typhoon\Collection\Exception\UndefinedKey');
    $this->_collection->$method('foo');
  }

  public function testSpl()
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