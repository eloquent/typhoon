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
use Eloquent\Typhoon\Type\IntegerType;
use Eloquent\Typhoon\Type\ObjectType;
use Eloquent\Typhoon\Type\StringType;
use Eloquent\Typhoon\Type\TraversableType;
use Eloquent\Typhoon\Type\TupleType;
use Eloquent\Typhoon\Type\Type;

class TyphaxTypeRendererTest extends \Eloquent\Typhoon\Test\TestCase
{
  protected function setUp()
  {
    parent::setUp();

    $this->_renderer = new TyphaxTypeRenderer;
  }

  /**
   * @return array
   */
  public function renderData()
  {
    $data = array();

    $typeFoo = Phake::mock('Eloquent\Typhoon\Type\NamedType');
    Phake::when($typeFoo)->typhoonName()->thenReturn('foo');
    $typeBar = Phake::mock('Eloquent\Typhoon\Type\NamedType');
    Phake::when($typeBar)->typhoonName()->thenReturn('bar');
    $typeBaz = Phake::mock('Eloquent\Typhoon\Type\NamedType');
    Phake::when($typeBaz)->typhoonName()->thenReturn('baz');

    // #0: Simple type
    $type = $typeFoo;
    $expected = 'foo';
    $data[] = array($expected, $type);

    // #1: Object type
    $type = new ObjectType(array(
      ObjectType::ATTRIBUTE_INSTANCE_OF => 'stdClass',
    ));
    $expected = 'stdClass';
    $data[] = array($expected, $type);

    // #2: Traversable type with no specific key-type or sub-type
    $type = new TraversableType(array(
      TraversableType::ATTRIBUTE_INSTANCE_OF => 'ArrayIterator',
    ));
    $expected = 'ArrayIterator';
    $data[] = array($expected, $type);

    // #3: Composite type
    $type = Phake::partialMock('Eloquent\Typhoon\Type\Composite\BaseCompositeType');
    Phake::when($type)->typhoonOperator()->thenReturn('~');
    $type->addTyphoonType($typeFoo);
    $type->addTyphoonType($typeBar);
    $type->addTyphoonType($typeBaz);
    $expected = 'foo~bar~baz';
    $data[] = array($expected, $type);

    // #4: Sub-typed type
    $type = new TupleType;
    $type->setTyphoonTypes(array(
      $typeFoo,
      $typeBar,
      $typeBaz,
    ));
    $expected = 'tuple<foo,bar,baz>';
    $data[] = array($expected, $type);

    // #5: Dynamic type with empty attributes
    $type = Phake::mock('Eloquent\Typhoon\Type\Dynamic\DynamicType');
    Phake::when($type)->typhoonName()->thenReturn('foo');
    Phake::when($type)->typhoonAttributes()->thenReturn(new Attributes);
    $expected = 'foo';
    $data[] = array($expected, $type);

    // #6: Dynamic type with lots of attributes
    $type = Phake::mock('Eloquent\Typhoon\Type\Dynamic\DynamicType');
    Phake::when($type)->typhoonName()->thenReturn('foo');
    Phake::when($type)->typhoonAttributes()->thenReturn(new Attributes(array(
      'bar' => 'rab',
      'baz' => '1',
      'qux' => '0.1',
      'doom' => 1,
      'splat' => 0.1,
      'ping' => null,
      'pong' => true,
      'pang' => false,
      'bip' => array(),
      'bop' => array('pib', 'pob', 'pab'),
      'bap' => array('flip' => 'flop', 'flap' => 'flep'),
    )));
    $expected = "foo(bar:rab,baz:'1',qux:'0.1',doom:1,splat:0.1,ping:null,pong:true,pang:false,bip:[],bop:[pib,pob,pab],bap:{flip:flop,flap:flep})";

    // #7: Type that is both sub-typed and dynamic
    $type = Phake::mock('Eloquent\Typhoon\Test\Fixture\DynamicSubTypedType');
    Phake::when($type)->typhoonName()->thenReturn('foo');
    Phake::when($type)->typhoonTypes()->thenReturn(array(
      $typeBar,
      $typeBaz,
    ));
    Phake::when($type)->typhoonAttributes()->thenReturn(new Attributes(array(
      'qux' => 'xuq',
      'doom' => 'mood',
    )));
    $expected = "foo<bar,baz>(qux:xuq,doom:mood)";
    $data[] = array($expected, $type);

    // #8: Nesting of subtypes and attributes
    $encodingStringType = new StringType(array(
      StringType::ATTRIBUTE_ENCODING => 'utf-8',
    ));
    $subTuple = new TupleType;
    $subTuple->setTyphoonTypes(array(
      $typeBar,
      $typeBaz,
    ));
    $type = new TupleType;
    $type->setTyphoonTypes(array(
      $typeFoo
      , $subTuple
      , $encodingStringType
    ));
    $expected = "tuple<foo,tuple<bar,baz>,string(encoding:utf-8)>";
    $data[] = array($expected, $type);

    return $data;
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
