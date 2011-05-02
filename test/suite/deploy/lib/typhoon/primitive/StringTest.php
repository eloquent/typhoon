<?php

namespace Typhoon\Primitive;

use PHPUnit_Framework_TestCase;

class StringTest extends PHPUnit_Framework_TestCase
{
  /**
   * @covers \Typhoon\Primitive\String::type
   * @group typhoon_scalars
   */
  public function testType()
  {
    $scalar = new String('');
    $this->assertInstanceOf('\Typhoon\Type\String', $scalar->type());
  }
}