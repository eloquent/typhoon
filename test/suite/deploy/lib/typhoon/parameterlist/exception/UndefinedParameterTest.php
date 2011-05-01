<?php

namespace Typhoon\ParameterList\Exception;

class UndefinedParameterTest extends \PHPUnit_Framework_TestCase
{
  protected function setUp()
  {
    $this->_index = 0;
    $this->_exception = new UndefinedParameter($this->_index);
    $this->_previous = new \Exception;
  }

  /**
   * @covers \Typhoon\ParameterList\Exception\UndefinedParameter::__construct
   */
  public function testConstructor()
  {
    $this->assertEquals('No parameter defined for index '.$this->_index.'.', $this->_exception->getMessage());

    $this->_exception = new UndefinedParameter($this->_index, $this->_previous);

    $this->assertSame($this->_previous, $this->_exception->getPrevious());
  }

  /**
   * @var NotImplemented
   */
  protected $_exception;

  /**
   * @var integer
   */
  protected $_index;

  /**
   * @var \Exception
   */
  protected $_previous;
}