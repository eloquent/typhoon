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

class TyphaxTypeRendererTest extends \Ezzatron\Typhoon\Test\TestCase
{
  protected function setUp()
  {
    parent::setUp();
    
    $this->_renderer = new TyphaxTypeRenderer;
    $this->_type = Phake::partialMock('Ezzatron\Typhoon\Type\Type');
    $this->_typeRegistry = new TypeRegistry;
  }

  /**
   * @covers Ezzatron\Typhoon\Type\Renderer\TyphaxTypeRenderer::render
   * @covers Ezzatron\Typhoon\Type\Renderer\TyphaxTypeRenderer::renderAlias
   */
  public function testRenderSimpleType()
  {
    $alias = 'foo';

    $this->_typeRegistry[$alias] = get_class($this->_type);
    $this->_renderer->setTypeRegistry($this->_typeRegistry);

    $this->assertEquals($alias, $this->_renderer->render($this->_type));
  }

  /**
   * @covers Ezzatron\Typhoon\Type\Renderer\TyphaxTypeRenderer::render
   * @covers Ezzatron\Typhoon\Type\Renderer\TyphaxTypeRenderer::renderAlias
   * @covers Ezzatron\Typhoon\Type\Renderer\TyphaxTypeRenderer::renderAttributes
   * @covers Ezzatron\Typhoon\Type\Renderer\TyphaxTypeRenderer::renderAttribute
   */
  public function testRenderDynamicType()
  {
    $alias = 'foo';
    $attributes = array(
      'bar' => 'baz',
      'qux' => 1,
      'doom' => .1,
    );

    $type = Phake::partialMock('Ezzatron\Typhoon\Type\Dynamic\DynamicType');
    Phake::when($type)->typhoonAttributes()->thenReturn($attributes);

    $this->_typeRegistry[$alias] = get_class($type);
    $this->_renderer->setTypeRegistry($this->_typeRegistry);

    $expected = "foo(bar='baz', qux=1, doom=0.1)";

    $this->assertEquals($expected, $this->_renderer->render($type));
  }

  /**
   * @covers Ezzatron\Typhoon\Type\Renderer\TyphaxTypeRenderer::render
   * @covers Ezzatron\Typhoon\Type\Renderer\TyphaxTypeRenderer::renderAlias
   * @covers Ezzatron\Typhoon\Type\Renderer\TyphaxTypeRenderer::renderAttributes
   */
  public function testRenderDynamicTypeNoAttributes()
  {
    $alias = 'foo';
    $attributes = array();

    $type = Phake::partialMock('Ezzatron\Typhoon\Type\Dynamic\DynamicType');
    Phake::when($type)->typhoonAttributes()->thenReturn($attributes);

    $this->_typeRegistry[$alias] = get_class($type);
    $this->_renderer->setTypeRegistry($this->_typeRegistry);

    $expected = 'foo';

    $this->assertEquals($expected, $this->_renderer->render($type));
  }

  /**
   * @covers Ezzatron\Typhoon\Type\Renderer\TyphaxTypeRenderer::render
   * @covers Ezzatron\Typhoon\Type\Renderer\TyphaxTypeRenderer::renderAlias
   */
  public function testRenderUnregistered()
  {
    $expected = 'unregistered_type<'.get_class($this->_type).'>';

    $this->assertEquals($expected, $this->_renderer->render($this->_type));
  }

  /**
   * @covers Ezzatron\Typhoon\Type\Renderer\TyphaxTypeRenderer::renderAttribute
   */
  public function testRenderAttributesFailure()
  {
    $type = Phake::partialMock('Ezzatron\Typhoon\Type\Dynamic\DynamicType');
    Phake::when($type)->typhoonAttributes()->thenReturn(array('foo'));

    $this->setExpectedException('Ezzatron\Typhoon\Assertion\Exception\UnexpectedArgumentException');
    $this->_renderer->render($type);
  }

  /**
   * @var TyphaxTypeRenderer
   */
  protected $_renderer;

  /**
   * @var Type
   */
  protected $_type;

  /**
   * @var TypeRegistry
   */
  protected $_typeRegistry;
}