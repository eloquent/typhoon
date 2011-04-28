<?php

namespace Typhoon\Type;

class StringTest extends \PHPUnit_Framework_TestCase
{
  protected function setUp()
  {
    $this->object = new String;
  }

  public function testString()
  {
    $this->assertEquals('string', $this->object->string());
  }

  /**
   * @var String
   */
  protected $object;
}