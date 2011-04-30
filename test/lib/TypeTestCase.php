<?php

namespace Typhoon\Test;

abstract class TypeTestCase extends \PHPUnit_Framework_TestCase
{
  protected function tearDown()
  {
    if ($this->_resource) fclose($this->_resource);
  }

  /**
   * @group typhoon_types
   */
  public function testString()
  {
    $this->assertEquals($this->expectedString(), $this->typeFixture()->string());
  }
  
  /**
   * @dataProvider validValues
   * @group typhoon_types
   */
  public function testCheckPass($value)
  {
    $this->assertTrue($this->typeFixture()->check($value));
  }
  
  /**
   * @dataProvider invalidValues
   * @group typhoon_types
   */
  public function testCheckFailure($value)
  {
    $this->assertFalse($this->typeFixture()->check($value));
  }
  
  /**
   * @return resource
   */
  protected function resourceFixture()
  {
    if (!$this->_resource) $this->_resource = fopen(__FILE__, 'rb');
    
    return $this->_resource;
  }
  
  /**
   * @return \Typhoon\Type
   */
  abstract protected function typeFixture();
  
  /**
   * @return string
   */
  abstract protected function expectedString();
  
  /**
   * @return array
   */
  abstract public function validValues();
  
  /**
   * @return array
   */
  abstract public function invalidValues();
  
  /**
   * @var resource
   */
  private $_resource;
}