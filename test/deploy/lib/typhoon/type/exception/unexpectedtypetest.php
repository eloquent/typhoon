<?php

namespace Typhoon\Type\Exception;

class UnexpectedTypeTest extends \PHPUnit_Framework_TestCase
{
  public function setUp()
  {
    $this->_exception = new UnexpectedType;
  }
  
  /**
   * @covers \Typhoon\Type\Exception\UnexpectedType::generateMessage
   */
  public function testGenerateMessage()
  {
    $this->assertEquals('Unexpected type.', $this->_exception->getMessage());
  }
  
  /**
   * @var UnexpectedType
   */
  protected $_exception;
}