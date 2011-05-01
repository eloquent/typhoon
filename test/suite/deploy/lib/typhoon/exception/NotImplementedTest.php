<?php

namespace Typhoon\Exception;

use Typhoon\Scalar\String;
use Typhoon\Test\ExceptionTestCase;

class NotImplementedTest extends ExceptionTestCase
{
  protected function setUp()
  {
    $this->_feature = new String('foo');
  }

  /**
   * @return string
   */
  protected function exceptionClass()
  {
    return __NAMESPACE__.'\NotImplemented';
  }

  /**
   * @return array
   */
  protected function defaultArguments()
  {
    return array($this->_feature);
  }

  /**
   * @covers \Typhoon\Exception\NotImplemented::__construct
   */
  public function testConstructor()
  {
    $this->assertEquals($this->_feature.' is not implemented.', $this->exceptionFixture()->getMessage());

    parent::testConstructor();
  }

  /**
   * @var String
   */
  protected $_feature;
}