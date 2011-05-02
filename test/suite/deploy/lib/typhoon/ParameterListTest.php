<?php

namespace Typhoon;

use ArrayIterator;
use PHPUnit_Framework_TestCase;

class ParameterListTest extends PHPUnit_Framework_TestCase
{
  /**
   * @return array
   */
  public function unexpectedArgumentData()
  {
    return array(
      array('offsetExists', array('foo')),
      array('offsetSet', array(1, null)),
      array('offsetSet', array('foo', new Parameter)),
      array('offsetGet', array('foo')),
    );
  }

  protected function setUp()
  {
    $this->_parameter_list = new ParameterList;
    $this->_parameter = new Parameter;
  }

  /**
   * @covers \Typhoon\ParameterList::offsetExists
   * @covers \Typhoon\ParameterList::offsetSet
   * @covers \Typhoon\ParameterList::offsetGet
   */
  public function testArrayAccess()
  {
    $this->assertInstanceOf('\ArrayAccess', $this->_parameter_list);

    $this->assertFalse(isset($this->_parameter_list[0]));

    $this->_parameter_list[] = $this->_parameter;

    $this->assertTrue(isset($this->_parameter_list[0]));
    $this->assertSame($this->_parameter, $this->_parameter_list[0]);

    $this->_parameter_list[1] = $this->_parameter;

    $this->assertTrue(isset($this->_parameter_list[1]));
    $this->assertSame($this->_parameter, $this->_parameter_list[1]);
  }

  /**
   * @covers \Typhoon\ParameterList::offsetGet
   */
  public function testOffsetGetFailure()
  {
    $this->setExpectedException(__NAMESPACE__.'\ParameterList\Exception\UndefinedParameter');
    $this->_parameter_list[0];
  }

  /**
   * @covers \Typhoon\ParameterList::offsetUnset
   */
  public function testOffsetUnsetFailure()
  {
    $parameter = new Parameter;
    $this->_parameter_list[] = $parameter;

    $this->setExpectedException(__NAMESPACE__.'\Exception\NotImplemented');
    unset($this->_parameter_list[0]);
  }

  /**
   * @covers \Typhoon\ParameterList::offsetExists
   * @covers \Typhoon\ParameterList::offsetSet
   * @covers \Typhoon\ParameterList::offsetGet
   * @covers \Typhoon\ParameterList::assertIndex
   * @dataProvider unexpectedArgumentData
   */
  public function testUnexpectedArgumentFailure($method, array $arguments)
  {
    $this->setExpectedException(__NAMESPACE__.'\ParameterList\Exception\UnexpectedArgument');
    call_user_func_array(array($this->_parameter_list, $method), $arguments);
  }

  /**
   * @covers \Typhoon\ParameterList::add
   * @covers \Typhoon\ParameterList::getIterator
   */
  public function testAddAndGetIterator()
  {
    $this->assertInstanceOf('\Iterator', $this->_parameter_list->getIterator());

    $expected = array();
    $this->assertEquals($expected, iterator_to_array($this->_parameter_list->getIterator()));

    $this->_parameter_list->add($this->_parameter);

    $expected[] = $this->_parameter;
    $this->assertEquals($expected, iterator_to_array($this->_parameter_list->getIterator()));
    
    $this->_parameter_list->add($this->_parameter);

    $expected[] = $this->_parameter;
    $this->assertEquals($expected, iterator_to_array($this->_parameter_list->getIterator()));

    $iterations = 0;
    foreach ($this->_parameter_list->getIterator() as $parameter)
    {
      $this->assertSame($this->_parameter, $parameter);

      $iterations++;
    }

    $this->assertEquals(2, $iterations);
  }

  public function testTraversable()
  {
    $this->assertInstanceOf('\Traversable', $this->_parameter_list);
  }

  /**
   * @var ParameterList
   */
  protected $_parameter_list;

  /**
   * @var Parameter
   */
  protected $_parameter;
}