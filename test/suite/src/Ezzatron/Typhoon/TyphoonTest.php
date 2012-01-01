<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ezzatron\Typhoon;

use Ezzatron\Typhoon\Renderer\Type as TypeRenderer;
use Ezzatron\Typhoon\Type\Inspector\TypeInspector;
use Ezzatron\Typhoon\Type\Registry\TypeRegistry;
use Phake;

class TyphoonTest extends \Ezzatron\Typhoon\Test\TestCase
{
  protected function setUp()
  {
    $this->_typhoon = new Typhoon;
    $this->_typeInspector = new TypeInspector;
    $this->_typeRegistry = new TypeRegistry;
    $this->_typeRenderer = Phake::mock('Ezzatron\Typhoon\Type\Renderer\TypeRenderer');
  }

  /**
   * @covers Ezzatron\Typhoon\Typhoon::instance
   * @covers Ezzatron\Typhoon\Typhoon::uninstall
   * @covers Ezzatron\Typhoon\Typhoon::install
   * @group core
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
   * @covers Ezzatron\Typhoon\Typhoon::setTypeInspector
   * @covers Ezzatron\Typhoon\Typhoon::typeInspector
   * @group core
   */
  public function testTypeInspector()
  {
    $this->assertInstanceOf('Ezzatron\Typhoon\Type\Inspector\TypeInspector', $this->_typhoon->typeInspector());

    $this->_typhoon->setTypeInspector($this->_typeInspector);

    $this->assertSame($this->_typeInspector, $this->_typhoon->typeInspector());
  }

  /**
   * @covers Ezzatron\Typhoon\Typhoon::setTypeRegistry
   * @covers Ezzatron\Typhoon\Typhoon::typeRegistry
   * @group core
   */
  public function testTypeRegistry()
  {
    $this->assertInstanceOf('Ezzatron\Typhoon\Type\Registry\TypeRegistry', $this->_typhoon->typeRegistry());

    $this->_typhoon->setTypeRegistry($this->_typeRegistry);

    $this->assertSame($this->_typeRegistry, $this->_typhoon->typeRegistry());
  }

  /**
   * @covers Ezzatron\Typhoon\Typhoon::setTypeRenderer
   * @covers Ezzatron\Typhoon\Typhoon::typeRenderer
   * @group core
   */
  public function testTypeRenderer()
  {
    $this->assertInstanceOf('Ezzatron\Typhoon\Type\Renderer\TyphaxTypeRenderer', $this->_typhoon->typeRenderer());

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
