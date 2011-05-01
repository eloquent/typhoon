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
    $this->setExpectedException('\Typhoon\Type\Exception\UnexpectedType');
    $this->_parameter_list[] = null;
  }

  /**
   * @covers \Typhoon\ParameterList::offsetSet
   */
  public function testOffsetSetFailure()
  {
    $this->markTestIncomplete('Unexpected argument is not yet implemented.');

    $this->setExpectedException('\Typhoon\Parameter\Exception\UnexpectedArgument');
    $this->_parameter_list[] = null;
  }

  /**
   * @covers \Typhoon\ParameterList::offsetGet
   */
  public function testOffsetGetFailure()
  {
    $this->setExpectedException('\Typhoon\ParameterList\Exception\UndefinedParameter');
    $this->_parameter_list[0];
  }

  /**
   * @covers \Typhoon\ParameterList::offsetUnset
   */
  public function testOffsetUnsetFailure()
  {
    $parameter = new Parameter;
    $this->_parameter_list[] = $parameter;

    $this->setExpectedException('\Typhoon\Exception\NotImplemented');
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

    $this->_parameter_list = $this->getMock('\Typhoon\ParameterList', array('iterator'));
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