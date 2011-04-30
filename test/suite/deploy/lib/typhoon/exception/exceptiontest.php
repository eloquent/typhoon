<?php

namespace Typhoon\Exception;

class ExceptionTest extends \PHPUnit_Framework_TestCase
{
  protected function setUp()
  {
    $this->_exception = $this->getMockForAbstractClass('\Typhoon\Exception\Exception', array(), '', false);
  }
  
  /**
   * @covers \Typhoon\Exception\Exception::__construct
   */
  public function testConstructor()
  {
    $this->_exception
      ->expects($this->once())
      ->method('generateMessage')
    ;
    
    $this->_exception->__construct();
  }
  
  /**
   * @covers \Typhoon\Exception\Exception::setPrevious
   * @covers \Typhoon\Exception\Exception::previous
   */
  public function testPrevious()
  {
    $this->assertNull($this->_exception->previous());
    
    $previous = new \Exception;
    $this->_exception->setPrevious($previous);
    
    $this->assertSame($previous, $this->_exception->previous());
  }
  
  /**
   * @var Exception
   */
  protected $_exception;
}