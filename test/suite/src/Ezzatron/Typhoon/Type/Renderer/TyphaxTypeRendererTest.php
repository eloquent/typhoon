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
use Ezzatron\Typhoon\Attribute\Attributes;
use Ezzatron\Typhoon\Type\Registry\TypeRegistry;
use Ezzatron\Typhoon\Type\MixedType;
use Ezzatron\Typhoon\Type\Type;

class TyphaxTypeRendererTest extends \Ezzatron\Typhoon\Test\TestCase
{
  /**
   * @return array
   */
  public function renderData()
  {
    $data = array();
    $renderer = new TyphaxTypeRenderer;

    // #0: Unregistered type
    $type = Phake::mock('Ezzatron\Typhoon\Type\Type');
    $alias = NULL;
    $expected = "unregistered(instanceOf='".get_class($type)."')";
    $data[] = array($type, $alias, $expected);

    // #1: Simple type
    $type = Phake::mock('Ezzatron\Typhoon\Type\Type');
    $alias = 'foo';
    $expected = 'foo';
    $data[] = array($type, $alias, $expected);

    // #2: Dynamic type
    $attributes = new Attributes(array(
      'bar' => 'baz',
      'qux' => 1,
      'doom' => .1,
    ));
    $type = Phake::mock('Ezzatron\Typhoon\Type\Dynamic\DynamicType');
    Phake::when($type)->typhoonAttributes()->thenReturn($attributes);
    $alias = 'foo';
    $expected = "foo(bar='baz',qux=1,doom=0.1)";
    $data[] = array($type, $alias, $expected);

    // #3: Dynamic type with no attributes
    $attributes = new Attributes;
    $type = Phake::mock('Ezzatron\Typhoon\Type\Dynamic\DynamicType');
    Phake::when($type)->typhoonAttributes()->thenReturn($attributes);
    $alias = 'foo';
    $expected = 'foo';
    $data[] = array($type, $alias, $expected);

    // #4: Traversable type with mixed type for both key and sub type
    $attributes = new Attributes;
    $keyType = new MixedType;
    $subType = new MixedType;
    $type = Phake::mock('Ezzatron\Typhoon\Type\Traversable\TraversableType');
    Phake::when($type)->typhoonKeyType()->thenReturn($keyType);
    Phake::when($type)->typhoonSubType()->thenReturn($subType);
    $alias = 'foo';
    $expected = 'foo';
    $data[] = array($type, $alias, $expected);

    // #5: Traversable type with mixed type for key type
    $attributes = new Attributes;
    $keyType = new MixedType;
    $subType = Phake::mock('Ezzatron\Typhoon\Type\Type');
    $type = Phake::mock('Ezzatron\Typhoon\Type\Traversable\TraversableType');
    Phake::when($type)->typhoonKeyType()->thenReturn($keyType);
    Phake::when($type)->typhoonSubType()->thenReturn($subType);
    $alias = 'foo';
    $expectedSub = $renderer->render($subType);
    $expected = 'foo<'.$expectedSub.'>';
    $data[] = array($type, $alias, $expected);

    // #6: Traversable type
    $attributes = new Attributes;
    $keyType = Phake::mock('Ezzatron\Typhoon\Type\Type');
    $subType = Phake::mock('Ezzatron\Typhoon\Type\Type');
    $type = Phake::mock('Ezzatron\Typhoon\Type\Traversable\TraversableType');
    Phake::when($type)->typhoonKeyType()->thenReturn($keyType);
    Phake::when($type)->typhoonSubType()->thenReturn($subType);
    $alias = 'foo';
    $expectedKey = $renderer->render($keyType);
    $expectedSub = $renderer->render($subType);
    $expected = 'foo<'.$expectedKey.','.$expectedSub.'>';
    $data[] = array($type, $alias, $expected);

    // #7: Dynamic traversable type
    $attributes = new Attributes(array(
      'bar' => 'baz',
      'qux' => 1,
      'doom' => .1,
    ));
    $keyType = Phake::mock('Ezzatron\Typhoon\Type\Type');
    $subType = Phake::mock('Ezzatron\Typhoon\Type\Type');
    $type = Phake::mock('Ezzatron\Typhoon\Test\Fixture\DynamicTraversable');
    Phake::when($type)->typhoonAttributes()->thenReturn($attributes);
    Phake::when($type)->typhoonKeyType()->thenReturn($keyType);
    Phake::when($type)->typhoonSubType()->thenReturn($subType);
    $alias = 'foo';
    $expectedKey = $renderer->render($keyType);
    $expectedSub = $renderer->render($subType);
    $expected = "foo(bar='baz',qux=1,doom=0.1)<".$expectedKey.','.$expectedSub.'>';
    $data[] = array($type, $alias, $expected);

    return $data;
  }

  protected function setUp()
  {
    parent::setUp();
    
    $this->_renderer = new TyphaxTypeRenderer;
    $this->_typeRegistry = new TypeRegistry;
  }

  /**
   * @covers Ezzatron\Typhoon\Type\Renderer\TyphaxTypeRenderer
   * @dataProvider renderData
   */
  public function testRender(Type $type, $alias, $expected)
  {
    if (null !== $alias)
    {
      $this->_typeRegistry[$alias] = $type;
    }
    $this->_renderer->setTypeRegistry($this->_typeRegistry);

    $this->assertEquals($expected, $this->_renderer->render($type));
  }

  /**
   * @var TyphaxTypeRenderer
   */
  protected $_renderer;

  /**
   * @var TypeRegistry
   */
  protected $_typeRegistry;
}