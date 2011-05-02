<?php

namespace Typhoon\ParameterList\Exception;

use Typhoon\Parameter;
use Typhoon\Primitive\Integer;
use Typhoon\Primitive\String;
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
    $expected =
      "Unexpected argument at index "
      .$this->_index
      ." - expected '"
      .$this->_expectedTypeName
      ."'."
    ;

    $this->assertEquals($expected, $this->exceptionFixture()->getMessage());

    $parameterName = new String('foo');
    $expected =
      "Unexpected argument at index "
      .$this->_index
      ." (".$parameterName.")"
      ." - expected '"
      .$this->_expectedTypeName
      ."'."
    ;

    $parameter = new Parameter;
    $parameter->setName($parameterName);

    $this->assertEquals($expected, $this->exceptionFixture(array($this->_value, $this->_index, $parameter))->getMessage());

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