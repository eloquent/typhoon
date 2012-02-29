<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon;

use Eloquent\Typhoon\Renderer\Type as TypeRenderer;
use Eloquent\Typhoon\Type\Inspector\TypeInspector;
use Eloquent\Typhoon\Type\Registry\TypeRegistry;
use Phake;

class TyphoonTest extends \Eloquent\Typhoon\Test\TestCase
{
  protected function setUp()
  {
    $this->_typhoon = new Typhoon;
    $this->_typeInspector = new TypeInspector;
    $this->_typeRegistry = new TypeRegistry;
    $this->_typeRenderer = Phake::mock('Eloquent\Typhoon\Type\Renderer\TypeRenderer');
  }

  /**
   * @covers Eloquent\Typhoon\Typhoon::instance
   * @covers Eloquent\Typhoon\Typhoon::uninstall
   * @covers Eloquent\Typhoon\Typhoon::install
   * @group core
   */
  public function testInstanceAndInstall()
  {
    Typhoon::uninstall();

    $instance = Typhoon::instance();

    $this->assertInstanceOf('Eloquent\Typhoon\Typhoon', $instance);
    $this->assertSame($instance, Typhoon::instance());

    $instance = new Typhoon;
    $instance->install();

    $this->assertSame($instance, Typhoon::instance());
  }

  /**
   * @covers Eloquent\Typhoon\Typhoon::setTypeInspector
   * @covers Eloquent\Typhoon\Typhoon::typeInspector
   * @group core
   */
  public function testTypeInspector()
  {
    $this->assertInstanceOf('Eloquent\Typhoon\Type\Inspector\TypeInspector', $this->_typhoon->typeInspector());

    $this->_typhoon->setTypeInspector($this->_typeInspector);

    $this->assertSame($this->_typeInspector, $this->_typhoon->typeInspector());
  }

  /**
   * @covers Eloquent\Typhoon\Typhoon::setTypeRegistry
   * @covers Eloquent\Typhoon\Typhoon::typeRegistry
   * @group core
   */
  public function testTypeRegistry()
  {
    $this->assertInstanceOf('Eloquent\Typhoon\Type\Registry\TypeRegistry', $this->_typhoon->typeRegistry());

    $this->_typhoon->setTypeRegistry($this->_typeRegistry);

    $this->assertSame($this->_typeRegistry, $this->_typhoon->typeRegistry());
  }

  /**
   * @covers Eloquent\Typhoon\Typhoon::setTypeRenderer
   * @covers Eloquent\Typhoon\Typhoon::typeRenderer
   * @group core
   */
  public function testTypeRenderer()
  {
    $this->assertInstanceOf('Eloquent\Typhoon\Type\Renderer\TyphaxTypeRenderer', $this->_typhoon->typeRenderer());

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
