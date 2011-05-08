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

use Typhoon\Test\TestCase;
use Typhoon\Type as TypeObject;
use Typhoon\TypeRegistry;

class TyphaxTest extends TestCase
{
  protected function setUp()
  {
    parent::setUp();
    
    $this->_renderer = new Typhax;
    $this->_type = $this->getMockForAbstractClass('Typhoon\Type');
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
   * @covers Typhoon\Renderer\Type\Typhax::renderArguments
   * @covers Typhoon\Renderer\Type\Typhax::renderArgument
   */
  public function testRenderTypeArguments()
  {
    $alias = 'foo';
    $argument_0 = 'bar';
    $argument_1 = 'baz';

    $type = $this->getMockForAbstractClass('Typhoon\Type', array($argument_0, $argument_1));

    $this->_typeRegistry[$alias] = get_class($type);
    $this->_renderer->setTypeRegistry($this->_typeRegistry);

    $expected =
      $alias
      .'('
      .var_export($argument_0, true)
      .', '
      .var_export($argument_1, true)
      .')'
    ;

    $this->assertEquals($expected, $this->_renderer->render($type));
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