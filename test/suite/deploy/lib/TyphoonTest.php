<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Typhoon\Renderer\Type as TypeRenderer;
use Typhoon\Test\TestCase;
use Typhoon\TypeRegistry;

class TyphoonTest extends TestCase
{
  protected function setUp()
  {
    $this->_typhoon = new Typhoon;
    $this->_typeRegistry = new TypeRegistry;
    $this->_typeRenderer = $this->getMockForAbstractClass('Typhoon\Renderer\Type');
  }

  /**
   * @covers Typhoon::instance
   * @covers Typhoon::uninstall
   * @covers Typhoon::install
   */
  public function testInstanceAndInstall()
  {
    Typhoon::uninstall();

    $instance = Typhoon::instance();

    $this->assertInstanceOf('Typhoon', $instance);
    $this->assertSame($instance, Typhoon::instance());

    $instance = new Typhoon;
    $instance->install();

    $this->assertSame($instance, Typhoon::instance());
  }

  /**
   * @covers Typhoon::typeAssertion
   */
  public function testTypeAssertion()
  {
    $this->assertInstanceOf('Typhoon\Assertion\Type', $this->_typhoon->typeAssertion());
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
   * @covers Typhoon::setTypeRenderer
   * @covers Typhoon::typeRenderer
   */
  public function testTypeRenderer()
  {
    $this->assertInstanceOf('Typhoon\Renderer\Type\Typhax', $this->_typhoon->typeRenderer());

    $this->_typhoon->setTypeRenderer($this->_typeRenderer);

    $this->assertSame($this->_typeRenderer, $this->_typhoon->typeRenderer());
  }

  /**
   * @var Typhoon
   */
  protected $_typhoon;


  /**
   * @var TypeRegistry
   */
  protected $_typeRegistry;

  /**
   * @var TypeRenderer
   */
  protected $_typeRenderer;
}