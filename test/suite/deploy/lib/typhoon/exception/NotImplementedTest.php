<?php

namespace Typhoon\Exception;

class NotImplementedTest extends \Typhoon\Test\ExceptionTestCase
{
  protected function setUp()
  {
    $this->_feature = 'foo';
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
   * @var string
   */
  protected $_feature;
}