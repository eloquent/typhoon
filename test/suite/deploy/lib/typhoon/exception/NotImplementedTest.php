<?php

namespace Typhoon\Exception;

use Typhoon\Scalar\String;
use Typhoon\Test\ExceptionTestCase;

class NotImplementedTest extends ExceptionTestCase
{
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

  protected function setUp()
  {
    $this->_feature = new String('foo');
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