<?php

namespace Typhoon\Scalar;

class StringTest extends \PHPUnit_Framework_TestCase
{
  /**
   * @covers \Typhoon\Scalar\String::type
   * @group typhoon_scalars
   */
  public function testType()
  {
    $scalar = new String('');
    $this->assertInstanceOf('\Typhoon\Type\String', $scalar->type());
  }
}