<?php

namespace Typhoon;

class TypeTest extends \PHPUnit_Framework_TestCase
{
  protected function setUp()
  {
    $this->object = new TestType;
  }
  
  public function testString()
  {
    $this->assertEquals('test', $this->object->string());
    $this->assertEquals('test', (string)$this->object);
  }

  /**
   * @var TestType
   */
  protected $object;
}

class TestType extends Type
{
  public function string()
  {
    return 'test';
  }
}