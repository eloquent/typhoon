<?php

namespace Typhoon\Exception;

class ExceptionTest extends \PHPUnit_Framework_TestCase
{
  /**
   * @covers \Typhoon\Exception\Exception::__construct
   * @covers \Typhoon\Exception\Exception::previous
   */
  public function testConstructor()
  {
    $exception = $this->getMockForAbstractClass('\Typhoon\Exception\Exception');

    $this->assertNull($exception->previous());
    
    $previous = new \Exception;
    $exception = $this->getMockForAbstractClass('\Typhoon\Exception\Exception', array($previous));
    
    $this->assertSame($previous, $exception->previous());
  }
}