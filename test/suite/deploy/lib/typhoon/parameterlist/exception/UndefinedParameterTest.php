<?php

namespace Typhoon\ParameterList\Exception;

use Typhoon\Primitive\Integer;
use Typhoon\Test\ExceptionTestCase;

class UndefinedParameterTest extends ExceptionTestCase
{
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

  protected function setUp()
  {
    $this->_index = new Integer(0);
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
   * @var Integer
   */
  protected $_index;
}