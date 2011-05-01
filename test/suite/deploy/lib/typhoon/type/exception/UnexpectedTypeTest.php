<?php

namespace Typhoon\Type\Exception;

class UnexpectedTypeTest extends \PHPUnit_Framework_TestCase
{
  protected function setUp()
  {
    $this->_expectedTypeName = 'foo';
    $this->_expectedType = $this->getMockForAbstractClass('\Typhoon\Type');
    $this->_expectedType
      ->expects($this->atLeastOnce())
      ->method('string')
      ->will($this->returnValue($this->_expectedTypeName))
    ;

    $this->_exception = new UnexpectedType($this->_expectedType);
    $this->_previous = new \Exception;
  }

  /**
   * @covers \Typhoon\Type\Exception\UnexpectedType::__construct
   */
  public function testConstructor()
  {
    $this->assertEquals("Unexpected type - expected '".$this->_expectedTypeName."'.", $this->_exception->getMessage());

    $this->_exception = new UnexpectedType($this->_expectedType, $this->_previous);

    $this->assertSame($this->_previous, $this->_exception->getPrevious());
  }

  /**
   * @covers \Typhoon\Type\Exception\UnexpectedType::expectedType
   */
  public function testExpectedType()
  {
    $this->assertSame($this->_expectedType, $this->_exception->expectedType());
  }

  /**
   * @var UnexpectedType
   */
  protected $_exception;

  /**
   * @var \Typhoon\Type
   */
  protected $_expectedType;

  /**
   * @var string
   */
  protected $_expectedTypeName;

  /**
   * @var \Exception
   */
  protected $_previous;
}