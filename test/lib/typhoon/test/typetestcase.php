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

use ReflectionClass;
use Typhoon;

abstract class TypeTestCase extends TestCase
{
  /**
   * @return Type
   */
  protected function typeFixture(array $attributes = null)
  {
    if (null === $attributes) $attributes = array();

    $class = $this->typeClass();
    $type = new $class;

    foreach ($attributes as $key => $value)
    {
      $type->setTyphoonAttribute($key, $value);
    }

    return $type;
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
  public function testCheck($expected, $value, $attributes = null)
  {
    $this->assertEquals($expected, $this->typeFixture($attributes)->check($value));
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