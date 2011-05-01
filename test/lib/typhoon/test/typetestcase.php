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
   * @dataProvider typeValues
   * @group typhoon_types
   */
  public function testCheck($expected, $value, $arguments = null)
  {
    if (null === $arguments) $arguments = array();

    $this->assertEquals($expected, $this->typeFixture($arguments)->check($value));
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
  abstract protected function typeFixture(array $arguments = null);
  
  /**
   * @return string
   */
  abstract protected function expectedString();
  
  /**
   * @return array
   */
  abstract public function typeValues();
  
  /**
   * @var resource
   */
  private $_resource;
}