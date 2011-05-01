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
   * @return Object
   */
  protected function typeFixture(array $arguments = null)
  {
    $class = $this->typeClass();

    if (null === $arguments) return new $class;

    $reflector = new \ReflectionClass($class);

    return $reflector->newInstanceArgs($arguments);
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
   * @return string
   */
  abstract protected function typeClass();

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