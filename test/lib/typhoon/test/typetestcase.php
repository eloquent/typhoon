<?php

namespace Typhoon\Test;

use PHPUnit_Framework_TestCase;
use ReflectionClass;
use Typhoon;

abstract class TypeTestCase extends PHPUnit_Framework_TestCase
{
  /**
   * @return Type
   */
  protected function typeFixture(array $arguments = null)
  {
    $class = $this->typeClass();

    if (null === $arguments) return new $class;

    $reflector = new ReflectionClass($class);

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

  protected function tearDown()
  {
    if ($this->_resource) fclose($this->_resource);
  }

  /**
   * @group typhoon_types
   */
  public function testString()
  {
    $this->assertEquals($this->expectedString(), (string)$this->typeFixture());
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