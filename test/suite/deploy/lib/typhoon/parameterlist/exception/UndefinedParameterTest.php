<?php

namespace Typhoon\ParameterList\Exception;

class UndefinedParameterTest extends \Typhoon\Test\ExceptionTestCase
{
  protected function setUp()
  {
    $this->_index = 0;
  }

  /**
   * @return string
   */
  protected function exceptionClass()
  {
    return __NAMESPACE__.'\UndefinedParameter';
  }

  /**
   * @return array
   */
  protected function defaultArguments()
  {
    return array($this->_index);
  }

  /**
   * @covers \Typhoon\ParameterList\Exception\UndefinedParameter::__construct
   */
  public function testConstructor()
  {
    $this->assertEquals('No parameter defined for index '.$this->_index.'.', $this->exceptionFixture()->getMessage());

    parent::testConstructor();
  }

  /**
   * @var integer
   */
  protected $_index;
}