<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Typhoon\Renderer;

use Typhoon;
use Typhoon\Test\TestCase;
use Typhoon\TypeRegistry;

class TypeTest extends TestCase
{
  protected function setUp()
  {
    parent::setUp();
    
    $this->_renderer = $this->getMockForAbstractClass('Typhoon\Renderer\Type');
    $this->_typeRegistry = new TypeRegistry;
  }

  /**
   * @covers Typhoon\Renderer\Type::setTypeRegistry
   * @covers Typhoon\Renderer\Type::typeRegistry
   */
  public function testTypeRegistry()
  {
    $typeRegistry = Typhoon::instance()->typeRegistry();

    $this->assertSame($typeRegistry, $this->_renderer->typeRegistry());

    $this->_renderer->setTypeRegistry($this->_typeRegistry);

    $this->assertSame($this->_typeRegistry, $this->_renderer->typeRegistry());
  }

  /**
   * @var Type
   */
  protected $_renderer;

  /**
   * @var TypeRegistry
   */
  protected $_typeRegistry;
}