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

use Ezzatron\Typhoon\Test\Fixture\Callable;
use Ezzatron\Typhoon\Test\Fixture\Stringable;
use Ezzatron\Typhoon\Test\Fixture\Traversable;
use Ezzatron\Typhoon\Typhoon;

class TestCase extends \PHPUnit_Framework_TestCase
{
  protected function setUp()
  {
    $typhoon = new Typhoon;
    $typhoon->install();
  }

  protected function tearDown()
  {
    if ($this->_socket)
    {
      @socket_close($this->_socket);
      $this->_socket = null;
    }
    if ($this->_stream)
    {
      @fclose($this->_stream);
      $this->_stream = null;
    }
    if ($this->_file)
    {
      @fclose($this->_file);
      $this->_file = null;
    }
    if ($this->_directory)
    {
      @fclose($this->_directory);
      $this->_directory = null;
    }
  }

  /**
   * @return resource
   */
  protected function resourceFixture()
  {
    if (null === $this->_resource)
    {
      $this->_resource = stream_context_create();
    }

    return $this->_resource;
  }

  /**
   * @return resource
   */
  protected function socketFixture()
  {
    if (null === $this->_socket)
    {
      $this->_socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
    }

    return $this->_socket;
  }

  /**
   * @return resource
   */
  protected function streamFixture()
  {
    if (null === $this->_stream)
    {
      $this->_stream = fopen('php://memory', 'wb');
    }

    return $this->_stream;
  }

  /**
   * @return resource
   */
  protected function fileFixture()
  {
    if (null === $this->_file)
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
    if (null === $this->_directory)
    {
      $this->_directory = opendir(__DIR__);
    }

    return $this->_directory;
  }

  /**
   * @param mixed $return
   *
   * @return Callable
   */
  protected function callableFixture($return = null)
  {
    return new Callable($return);
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

  /**
   * @param array $values
   *
   * @return Traversable
   */
  protected function traversableFixture(array $values = null)
  {
    return new Traversable($values);
  }

  /**
   * @var resource
   */
  private $_resource;

  /**
   * @var resource
   */
  private $_socket;

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