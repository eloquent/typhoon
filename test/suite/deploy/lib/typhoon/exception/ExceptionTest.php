<?php

namespace Typhoon\Exception;

class ExceptionTest extends \PHPUnit_Framework_TestCase
{
  public function setUp()
  {
    $this->_message = 'foo';
    $this->_previous = new \Exception();
  }
  /**
   * @covers \Typhoon\Exception\Exception::__construct
   */
  public function testConstructor()
  {
    $exception = $this->getMockForAbstractClass('\Typhoon\Exception\Exception', array($this->_message));

    $this->assertEquals($this->_message, $exception->getMessage());
    $this->assertNull($exception->getPrevious());

    $exception = $this->getMockForAbstractClass('\Typhoon\Exception\Exception', array($this->_message, $this->_previous));

    $this->assertEquals($this->_message, $exception->getMessage());
    $this->assertSame($this->_previous, $exception->getPrevious());
  }

  /**
   * @var string
   */
  protected $_message;

  /**
   * @var \Exception
   */
  protected $_previous;
}