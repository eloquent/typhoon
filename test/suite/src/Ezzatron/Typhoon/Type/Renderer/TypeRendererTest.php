<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ezzatron\Typhoon\Type\Renderer;

use Phake;
use Ezzatron\Typhoon\Type\Registry\TypeRegistry;
use Ezzatron\Typhoon\Typhoon;

class TypeRendererTest extends \Ezzatron\Typhoon\Test\TestCase
{
  protected function setUp()
  {
    parent::setUp();
    
    $this->_renderer = Phake::partialMock(__NAMESPACE__.'\TypeRenderer');
    $this->_typeRegistry = new TypeRegistry;
  }

  /**
   * @covers Ezzatron\Typhoon\Type\Renderer\TypeRenderer::setTypeRegistry
   * @covers Ezzatron\Typhoon\Type\Renderer\TypeRenderer::typeRegistry
   * @group type
   * @group type-renderer
   * @group core
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