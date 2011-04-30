<?php

namespace Typhoon\Type\Exception;

class UnexpectedTypeTest extends \PHPUnit_Framework_TestCase
{
  /**
   * @covers \Typhoon\Type\Exception\UnexpectedType::__construct
   */
  public function testConstructor()
  {
    $exception = new UnexpectedType;
    $this->assertEquals('Unexpected type.', $exception->getMessage());
  }
}