<?php

namespace Typhoon\Exception;

class ExceptionTest extends \Typhoon\Test\ExceptionTestCase
{
  protected function setUp()
  {
    $this->_message = 'foo';
  }

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
  
  /**
   * @covers \Typhoon\Exception\Exception::__construct
   */
  public function testConstructor()
  {
    $this->assertEquals($this->_message, $this->exceptionFixture()->getMessage());

    parent::testConstructor();
  }

  /**
   * @var string
   */
  protected $_message;
}