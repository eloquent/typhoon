<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ezzatron\Typhoon\Test;

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
      $type->typhoonAttributes()->set($key, $value);
    }

    return $type;
  }

  /**
   * @return resource
   */
  protected function resourceFixture()
  {
    if (!$this->_resource) $this->_resource = stream_context_create();

    return $this->_resource;
  }

  /**
   * @return resource
   */
  protected function streamFixture()
  {
    if (!$this->_stream) $this->_stream = fopen(__FILE__, 'rb');

    return $this->_stream;
  }

  protected function tearDown()
  {
    if ($this->_stream) fclose($this->_stream);
  }

  /**
   * @dataProvider typeValues
   * @group typhoon_types
   */
  public function testTyphoonCheck($expected, $value, $attributes = null)
  {
    $this->assertEquals($expected, $this->typeFixture($attributes)->typhoonCheck($value));
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

  /**
   * @var resource
   */
  private $_stream;
}