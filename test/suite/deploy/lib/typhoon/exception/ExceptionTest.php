<?php

namespace Typhoon\Exception;

use Typhoon\Scalar\String;
use Typhoon\Test\ExceptionTestCase;

class ExceptionTest extends ExceptionTestCase
{
  /**
   * @return string
   */
  protected function exceptionClass()
  {
    return __NAMESPACE__.'\Exception';
  }

  /**
   * @return array
   */
  protected function defaultArguments()
  {
    return array($this->_message);
  }

  protected function setUp()
  {
    $this->_message = new String('foo');
  }
  
  /**
   * @covers \Typhoon\Exception\Exception::__construct
   */
  public function testConstructor()
  {
    $this->assertEquals((string)$this->_message, $this->exceptionFixture()->getMessage());

    parent::testConstructor();
  }

  /**
   * @var String
   */
  protected $_message;
}