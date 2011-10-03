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

use Ezzatron\Typhoon\Attribute\Attributes;
use Ezzatron\Typhoon\Test\Fixture\Stringable;
use ReflectionClass;
use vfsStream;

abstract class TypeTestCase extends TestCase
{
  /**
   * @return Type
   */
  protected function typeFixture(Attributes $attributes = null)
  {
    $class = $this->typeClass();

    return new $class($attributes);
  }

  /**
   * @return resource
   */
  protected function resourceFixture()
  {
    if (!$this->_resource)
    {
      $this->_resource = stream_context_create();
    }

    return $this->_resource;
  }

  /**
   * @return resource
   */
  protected function streamFixture()
  {
    if (!$this->_stream)
    {
      vfsStream::setup('root');
      
      $streamUrl = vfsStream::url('root/streamFixture');
      file_put_contents($streamUrl, '');
      
      $this->_stream = fopen($streamUrl, 'rb');
    }

    return $this->_stream;
  }

  /**
   * @return resource
   */
  protected function fileFixture()
  {
    if (!$this->_file)
    {
      $this->_file = fopen(__FILE__, 'rb');
    }

    return $this->_file;
  }

  /**
   * @return resource
   */
  protected function directoryFixture()
  {
    if (!$this->_directory)
    {
      $this->_directory = opendir(__DIR__);
    }

    return $this->_directory;
  }

  /**
   * @param string $string
   * 
   * @return Stringable
   */
  protected function stringableFixture($string = null)
  {
    return new Stringable($string);
  }

  protected function setUp()
  {
    $reflector = new ReflectionClass('Ezzatron\Typhoon\Type\Dynamic\BaseDynamicType');
    $property = $reflector->getProperty('attributeSignatures');
    $property->setAccessible(true);
    $property->setValue(null, array());
  }

  protected function tearDown()
  {
    if ($this->_stream)
    {
      fclose($this->_stream);
    }
    if ($this->_file)
    {
      fclose($this->_file);
    }
    if ($this->_directory)
    {
      fclose($this->_directory);
    }
  }

  /**
   * @dataProvider typeValues
   * @group typhoon_types
   */
  public function testTyphoonCheck($expected, $value, Attributes $attributes = null)
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

  /**
   * @var resource
   */
  private $_file;

  /**
   * @var resource
   */
  private $_directory;
}