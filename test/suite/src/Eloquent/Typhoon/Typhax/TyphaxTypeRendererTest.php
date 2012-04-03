<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Typhax;

use Phake;
use Eloquent\Typhoon\Attribute\Attributes;
use Eloquent\Typhoon\Type\Registry\TypeRegistry;
use Eloquent\Typhoon\Type\MixedType;
use Eloquent\Typhoon\Type\ObjectType;
use Eloquent\Typhoon\Type\TraversableType;
use Eloquent\Typhoon\Type\Type;

class TyphaxTypeRendererTest extends \Eloquent\Typhoon\Test\TestCase
{
  /**
   * @return array
   */
  public function renderData()
  {
    $data = array();

    // #0: Simple type
    $type = Phake::mock('Eloquent\Typhoon\Type\NamedType');
    Phake::when($type)->typhoonName()->thenReturn('foo');
    $expected = 'foo';
    $data[] = array($expected, $type);

    // #1: Dynamic type
    $attributes = new Attributes(array(
      'bar' => 'baz',
      'qux' => 1,
      'doom' => .1,
    ));
    $type = Phake::mock('Eloquent\Typhoon\Type\Dynamic\DynamicType');
    Phake::when($type)->typhoonName()->thenReturn('foo');
    Phake::when($type)->typhoonAttributes()->thenReturn($attributes);
    $expected = "foo(bar:baz,qux:1,doom:0.1)";
    $data[] = array($expected, $type);

    // #2: Dynamic type with no attributes
    $attributes = new Attributes;
    $type = Phake::mock('Eloquent\Typhoon\Type\Dynamic\DynamicType');
    Phake::when($type)->typhoonName()->thenReturn('foo');
    Phake::when($type)->typhoonAttributes()->thenReturn($attributes);
    $expected = 'foo';
    $data[] = array($expected, $type);

    // #3: Traversable type with mixed type for both key and sub type
    $attributes = new Attributes;
    $keyType = new MixedType;
    $subType = new MixedType;
    $type = Phake::mock('Eloquent\Typhoon\Type\SubTyped\TraversableType');
    Phake::when($type)->typhoonName()->thenReturn('foo');
    Phake::when($type)->typhoonTypes()->thenReturn(array());
    $expected = 'foo';
    $data[] = array($expected, $type);

    // #4: Traversable type with mixed type for key type
    $attributes = new Attributes;
    $keyType = new MixedType;
    $subType = Phake::mock('Eloquent\Typhoon\Type\NamedType');
    Phake::when($subType)->typhoonName()->thenReturn('bar');
    $type = Phake::mock('Eloquent\Typhoon\Type\SubTyped\TraversableType');
    Phake::when($type)->typhoonName()->thenReturn('foo');
    Phake::when($type)->typhoonTypes()->thenReturn(array($subType));
    $expected = 'foo<bar>';
    $data[] = array($expected, $type);

    // #5: Traversable type
    $attributes = new Attributes;
    $keyType = Phake::mock('Eloquent\Typhoon\Type\NamedType');
    Phake::when($keyType)->typhoonName()->thenReturn('bar');
    $subType = Phake::mock('Eloquent\Typhoon\Type\NamedType');
    Phake::when($subType)->typhoonName()->thenReturn('baz');
    $type = Phake::mock('Eloquent\Typhoon\Type\SubTyped\TraversableType');
    Phake::when($type)->typhoonName()->thenReturn('foo');
    Phake::when($type)->typhoonTypes()->thenReturn(array($keyType, $subType));
    $expected = 'foo<bar,baz>';
    $data[] = array($expected, $type);

    // #6: Dynamic traversable type
    $attributes = new Attributes(array(
      'bar' => 'baz',
      'qux' => 1,
      'doom' => .1,
    ));
    $keyType = Phake::mock('Eloquent\Typhoon\Type\NamedType');
    Phake::when($keyType)->typhoonName()->thenReturn('bar');
    $subType = Phake::mock('Eloquent\Typhoon\Type\NamedType');
    Phake::when($subType)->typhoonName()->thenReturn('baz');
    $type = Phake::mock('Eloquent\Typhoon\Test\Fixture\DynamicTraversable');
    Phake::when($type)->typhoonName()->thenReturn('foo');
    Phake::when($type)->typhoonAttributes()->thenReturn($attributes);
    Phake::when($type)->typhoonTypes()->thenReturn(array($keyType, $subType));
    $expected = "foo<bar,baz>(bar:baz,qux:1,doom:0.1)";
    $data[] = array($expected, $type);

    // #7: Object type
    $type = new ObjectType;
    $expected = 'object';
    $data[] = array($expected, $type);

    // #8: Traversable type
    $type = new TraversableType;
    $expected = 'traversable';
    $data[] = array($expected, $type);

    // #9: Object type of particular class
    $type = new ObjectType(array(
      ObjectType::ATTRIBUTE_INSTANCE_OF => 'Bar',
    ));
    $expected = 'Bar';
    $data[] = array($expected, $type);

    // #10: Traversable type of particular class
    $keyType = Phake::mock('Eloquent\Typhoon\Type\NamedType');
    Phake::when($keyType)->typhoonName()->thenReturn('baz');
    $subType = Phake::mock('Eloquent\Typhoon\Type\NamedType');
    Phake::when($subType)->typhoonName()->thenReturn('qux');
    $type = new TraversableType(array(
      TraversableType::ATTRIBUTE_INSTANCE_OF => 'Bar',
    ));
    $type->setTyphoonKeyType($keyType);
    $type->setTyphoonSubType($subType);
    $expected = "Bar<baz,qux>";
    $data[] = array($expected, $type);

    // #11: Dynamic type with numeric strings
    $attributes = new Attributes(array(
      'bar' => '1',
      'baz' => '0.1',
    ));
    $type = Phake::mock('Eloquent\Typhoon\Type\Dynamic\DynamicType');
    Phake::when($type)->typhoonName()->thenReturn('foo');
    Phake::when($type)->typhoonAttributes()->thenReturn($attributes);
    $expected = "foo(bar:'1',baz:'0.1')";
    $data[] = array($expected, $type);

    return $data;
  }

  protected function setUp()
  {
    parent::setUp();

    $this->_renderer = new TyphaxTypeRenderer;
  }

  /**
   * @covers Eloquent\Typhoon\Typhax\TyphaxTypeRenderer
   * @covers Eloquent\Typhoon\Type\Renderer\TypeRenderer
   * @dataProvider renderData
   * @group type
   * @group type-renderer
   * @group core
   */
  public function testRender($expected, Type $type)
  {
    $this->assertEquals($expected, $this->_renderer->render($type));
  }

  /**
   * @var TyphaxTypeRenderer
   */
  protected $_renderer;
}
