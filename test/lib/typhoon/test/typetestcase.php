<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
   * @return array
   */
  abstract public function typeValues();
  
  /**
   * @var resource
   */
  private $_resource;
}