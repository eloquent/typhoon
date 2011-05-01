<?php

namespace Typhoon;

class ParameterListTest extends \PHPUnit_Framework_TestCase
{
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
   * @covers \Typhoon\ParameterList::offsetSet
   */
  public function testOffsetSetFailureInterim()
  {
    $this->setExpectedException(__NAMESPACE__.'\Type\Exception\UnexpectedType');
    $this->_parameter_list[] = null;
  }

  /**
   * @covers \Typhoon\ParameterList::offsetSet
   */
  public function testOffsetSetFailure()
  {
    $this->markTestIncomplete('Unexpected argument is not yet implemented.');

    $this->setExpectedException(__NAMESPACE__.'\Parameter\Exception\UnexpectedArgument');
    $this->_parameter_list[] = null;
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
   * @covers \Typhoon\ParameterList::add
   * @covers \Typhoon\ParameterList::iterator
   */
  public function testAddAndIterator()
  {
    $this->assertInstanceOf('\Iterator', $this->_parameter_list->iterator());

    $expected = array();
    $this->assertEquals($expected, iterator_to_array($this->_parameter_list->iterator()));

    $this->_parameter_list->add($this->_parameter);

    $expected[] = $this->_parameter;
    $this->assertEquals($expected, iterator_to_array($this->_parameter_list->iterator()));
    
    $this->_parameter_list->add($this->_parameter);

    $expected[] = $this->_parameter;
    $this->assertEquals($expected, iterator_to_array($this->_parameter_list->iterator()));

    $iterations = 0;
    foreach ($this->_parameter_list->iterator() as $parameter)
    {
      $this->assertSame($this->_parameter, $parameter);

      $iterations++;
    }

    $this->assertEquals(2, $iterations);
  }

  /**
   * @covers \Typhoon\ParameterList::getIterator
   */
  public function testTraversable()
  {
    $this->assertInstanceOf('\Traversable', $this->_parameter_list);

    $this->_parameter_list = $this->getMock(__NAMESPACE__.'\ParameterList', array('iterator'));
    $this->_parameter_list
      ->expects($this->once())
      ->method('iterator')
      ->will($this->returnValue(new \ArrayIterator(array())))
    ;

    foreach ($this->_parameter_list as $paramter) {}
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