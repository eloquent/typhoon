<?php

namespace Typhoon\Type\Exception;

class UnexpectedTypeTest extends \Typhoon\Test\ExceptionTestCase
{
  protected function setUp()
  {
    $this->_expectedTypeName = 'foo';
  }

  /**
   * @return string
   */
  protected function exceptionClass()
  {
    return __NAMESPACE__.'\UnexpectedType';
  }

  /**
   * @return array
   */
  protected function defaultArguments()
  {
    return array($this->typeFixture());
  }

  /**
   * @return \Typhoon\Type
   */
  protected function typeFixture()
  {
    $type = $this->getMockForAbstractClass('\Typhoon\Type');
    $type
      ->expects($this->once())
      ->method('string')
      ->will($this->returnValue($this->_expectedTypeName))
    ;

    return $type;
  }

  /**
   * @covers \Typhoon\Type\Exception\UnexpectedType::__construct
   */
  public function testConstructor()
  {
    $this->assertEquals("Unexpected type - expected '".$this->_expectedTypeName."'.", $this->exceptionFixture()->getMessage());

    parent::testConstructor();
  }

  /**
   * @covers \Typhoon\Type\Exception\UnexpectedType::expectedType
   */
  public function testExpectedType()
  {
    $expectedType = $this->typeFixture();

    $this->assertSame($expectedType, $this->exceptionFixture(array($expectedType))->expectedType());
  }

  /**
   * @var string
   */
  protected $_expectedTypeName;
}