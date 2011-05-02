<?php

namespace Typhoon\ParameterList\Exception;

use Typhoon\Parameter;
use Typhoon\Scalar\Integer;
use Typhoon\Test\ExceptionTestCase;

class UnexpectedArgumentTest extends ExceptionTestCase
{
  /**
   * @return string
   */
  protected function exceptionClass()
  {
    return __NAMESPACE__.'\UnexpectedArgument';
  }

  /**
   * @return array
   */
  protected function defaultArguments()
  {
    return array($this->_value, $this->_index, $this->_parameter);
  }

  protected function setUp()
  {
    $this->_value = 'foo';
    $this->_index = new Integer(0);
    $this->_parameter = new Parameter;
    $this->_expectedTypeName = (string)$this->_parameter->type();
  }

  /**
   * @covers \Typhoon\ParameterList\Exception\UnexpectedArgument::__construct
   */
  public function testConstructor()
  {
    $this->assertEquals("Unexpected argument at index ".$this->_index." - expected '".$this->_expectedTypeName."'.", $this->exceptionFixture()->getMessage());

    parent::testConstructor();
  }

  /**
   * @var mixed
   */
  protected $_value;

  /**
   * @var Integer
   */
  protected $_index;

  /**
   * @var Parameter
   */
  protected $_parameter;

  /**
   * @var string
   */
  protected $_expectedTypeName;
}