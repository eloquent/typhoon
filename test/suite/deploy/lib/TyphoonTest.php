<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Typhoon\TypeRegistry;

class TyphoonTest extends PHPUnit_Framework_TestCase
{
  protected function setUp()
  {
    $this->_typhoon = new Typhoon;
    $this->_typeRegistry = new TypeRegistry;
  }

  /**
   * @covers Typhoon::instance
   */
  public function testInstance()
  {
    $instance = Typhoon::instance();

    $this->assertInstanceOf('Typhoon', $instance);
    $this->assertSame($instance, Typhoon::instance());
  }

  /**
   * @covers Typhoon::setTypeRegistry
   * @covers Typhoon::typeRegistry
   */
  public function testTypeRegistry()
  {
    $this->assertInstanceOf('Typhoon\TypeRegistry', $this->_typhoon->typeRegistry());

    $this->_typhoon->setTypeRegistry($this->_typeRegistry);

    $this->assertSame($this->_typeRegistry, $this->_typhoon->typeRegistry());
  }

  /**
   * @var Typhoon
   */
  protected $_typhoon;

  /**
   * @var TypeRegistry
   */
  protected $_typeRegistry;
}