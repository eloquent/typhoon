<?php

namespace Typhoon\Scalar;

class IntegerTest extends \PHPUnit_Framework_TestCase
{
  /**
   * @covers \Typhoon\Scalar\Integer::type
   * @group typhoon_scalars
   */
  public function testType()
  {
    $scalar = new Integer(1);
    $this->assertInstanceOf('\Typhoon\Type\Integer', $scalar->type());
  }
}