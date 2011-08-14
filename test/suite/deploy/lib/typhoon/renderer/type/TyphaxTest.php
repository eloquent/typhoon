<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Typhoon\Renderer\Type;

use Phake;
use Typhoon\Test\TestCase;
use Typhoon\Type as TypeObject;
use Typhoon\TypeRegistry;

class TyphaxTest extends TestCase
{
  protected function setUp()
  {
    parent::setUp();
    
    $this->_renderer = new Typhax;
    $this->_type = Phake::partialMock('Typhoon\Type');
    $this->_typeRegistry = new TypeRegistry;
  }

  /**
   * @covers Typhoon\Renderer\Type\Typhax::render
   * @covers Typhoon\Renderer\Type\Typhax::renderAlias
   */
  public function testRenderSimpleType()
  {
    $alias = 'foo';

    $this->_typeRegistry[$alias] = get_class($this->_type);
    $this->_renderer->setTypeRegistry($this->_typeRegistry);

    $this->assertEquals($alias, $this->_renderer->render($this->_type));
  }

  /**
   * @covers Typhoon\Renderer\Type\Typhax::render
   * @covers Typhoon\Renderer\Type\Typhax::renderAlias
   * @covers Typhoon\Renderer\Type\Typhax::renderAttributes
   * @covers Typhoon\Renderer\Type\Typhax::renderAttribute
   */
  public function testRenderDynamicType()
  {
    $alias = 'foo';
    $attributes = array(
      'bar' => 'baz',
      'qux' => 1,
      'doom' => .1,
    );

    $type = Phake::partialMock('Typhoon\DynamicType');
    Phake::when($type)->typhoonAttributes()->thenReturn($attributes);

    $this->_typeRegistry[$alias] = get_class($type);
    $this->_renderer->setTypeRegistry($this->_typeRegistry);

    $expected = "foo(bar='baz', qux=1, doom=0.1)";

    $this->assertEquals($expected, $this->_renderer->render($type));
  }

  /**
   * @covers Typhoon\Renderer\Type\Typhax::render
   * @covers Typhoon\Renderer\Type\Typhax::renderAlias
   * @covers Typhoon\Renderer\Type\Typhax::renderAttributes
   */
  public function testRenderDynamicTypeNoAttributes()
  {
    $alias = 'foo';
    $attributes = array();

    $type = Phake::partialMock('Typhoon\DynamicType');
    Phake::when($type)->typhoonAttributes()->thenReturn($attributes);

    $this->_typeRegistry[$alias] = get_class($type);
    $this->_renderer->setTypeRegistry($this->_typeRegistry);

    $expected = 'foo';

    $this->assertEquals($expected, $this->_renderer->render($type));
  }

  /**
   * @covers Typhoon\Renderer\Type\Typhax::render
   * @covers Typhoon\Renderer\Type\Typhax::renderAlias
   */
  public function testRenderUnregistered()
  {
    $expected = 'unregistered_type<'.get_class($this->_type).'>';

    $this->assertEquals($expected, $this->_renderer->render($this->_type));
  }

  /**
   * @covers Typhoon\Renderer\Type\Typhax::renderAttribute
   */
  public function testRenderAttributesFailure()
  {
    $type = Phake::partialMock('Typhoon\DynamicType');
    Phake::when($type)->typhoonAttributes()->thenReturn(array('foo'));

    $this->setExpectedException('Typhoon\Assertion\Exception\UnexpectedArgument');
    $this->_renderer->render($type);
  }

  /**
   * @var Typhax
   */
  protected $_renderer;

  /**
   * @var TypeObject
   */
  protected $_type;

  /**
   * @var TypeRegistry
   */
  protected $_typeRegistry;
}