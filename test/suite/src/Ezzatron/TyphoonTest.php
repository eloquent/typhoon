<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Ezzatron\Typhoon\Renderer\Type as TypeRenderer;
use Ezzatron\Typhoon\Test\TestCase;
use Ezzatron\Typhoon\TypeInspector;
use Ezzatron\Typhoon\TypeRegistry;
use Ezzatron\Typhoon\Typhoon;

class TyphoonTest extends TestCase
{
  protected function setUp()
  {
    $this->_typhoon = new Typhoon;
    $this->_typeInspector = new TypeInspector;
    $this->_typeRegistry = new TypeRegistry;
    $this->_typeRenderer = Phake::mock('Ezzatron\Typhoon\Renderer\Type');
  }

  /**
   * @covers Ezzatron\Typhoon\Typhoon::instance
   * @covers Ezzatron\Typhoon\Typhoon::uninstall
   * @covers Ezzatron\Typhoon\Typhoon::install
   */
  public function testInstanceAndInstall()
  {
    Typhoon::uninstall();

    $instance = Typhoon::instance();

    $this->assertInstanceOf('Ezzatron\Typhoon\Typhoon', $instance);
    $this->assertSame($instance, Typhoon::instance());

    $instance = new Typhoon;
    $instance->install();

    $this->assertSame($instance, Typhoon::instance());
  }

  /**
   * @covers Ezzatron\Typhoon\Typhoon::typeAssertion
   */
  public function testTypeAssertion()
  {
    $this->assertInstanceOf('Ezzatron\Typhoon\Assertion\Type', $this->_typhoon->typeAssertion());
  }

  /**
   * @covers Ezzatron\Typhoon\Typhoon::setTypeInspector
   * @covers Ezzatron\Typhoon\Typhoon::typeInspector
   */
  public function testTypeInspector()
  {
    $this->assertInstanceOf('Ezzatron\Typhoon\TypeInspector', $this->_typhoon->typeInspector());

    $this->_typhoon->setTypeInspector($this->_typeInspector);

    $this->assertSame($this->_typeInspector, $this->_typhoon->typeInspector());
  }

  /**
   * @covers Ezzatron\Typhoon\Typhoon::setTypeRegistry
   * @covers Ezzatron\Typhoon\Typhoon::typeRegistry
   */
  public function testTypeRegistry()
  {
    $this->assertInstanceOf('Ezzatron\Typhoon\TypeRegistry', $this->_typhoon->typeRegistry());

    $this->_typhoon->setTypeRegistry($this->_typeRegistry);

    $this->assertSame($this->_typeRegistry, $this->_typhoon->typeRegistry());
  }

  /**
   * @covers Ezzatron\Typhoon\Typhoon::setTypeRenderer
   * @covers Ezzatron\Typhoon\Typhoon::typeRenderer
   */
  public function testTypeRenderer()
  {
    $this->assertInstanceOf('Ezzatron\Typhoon\Renderer\Type\Typhax', $this->_typhoon->typeRenderer());

    $this->_typhoon->setTypeRenderer($this->_typeRenderer);

    $this->assertSame($this->_typeRenderer, $this->_typhoon->typeRenderer());
  }

  /**
   * @var Typhoon
   */
  protected $_typhoon;

  /**
   * @var TypeInspector
   */
  protected $_typeInspector;

  /**
   * @var TypeRegistry
   */
  protected $_typeRegistry;

  /**
   * @var TypeRenderer
   */
  protected $_typeRenderer;
}