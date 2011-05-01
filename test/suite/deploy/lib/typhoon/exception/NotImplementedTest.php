<?php

namespace Typhoon\Exception;

class NotImplementedTest extends \PHPUnit_Framework_TestCase
{
  protected function setUp()
  {
    $this->_feature = 'foo';
    $this->_exception = new NotImplemented($this->_feature);
    $this->_previous = new \Exception;
  }

  /**
   * @covers \Typhoon\Exception\NotImplemented::__construct
   */
  public function testConstructor()
  {
    $this->assertEquals($this->_feature.' is not implemented.', $this->_exception->getMessage());

    $this->_exception = new NotImplemented($this->_feature, $this->_previous);

    $this->assertSame($this->_previous, $this->_exception->getPrevious());
  }

  /**
   * @var NotImplemented
   */
  protected $_exception;

  /**
   * @var string
   */
  protected $_feature;

  /**
   * @var \Exception
   */
  protected $_previous;
}