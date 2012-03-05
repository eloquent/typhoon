<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Parser;

use Eloquent\Typhax\AST\Composite as TyphaxComposite;
use Eloquent\Typhax\AST\Node as TyphaxNode;
use Eloquent\Typhax\AST\Type as TyphaxType;
use Eloquent\Typhoon\Type\Composite\AndType;
use Eloquent\Typhoon\Type\Composite\OrType;
use Eloquent\Typhoon\Type\Registry\TypeRegistry;
use Eloquent\Typhoon\Type\ClassType;;
use Eloquent\Typhoon\Type\NullType;
use Eloquent\Typhoon\Type\StringType;
use Eloquent\Typhoon\Type\TraversableType;
use Eloquent\Typhoon\Type\Type;
use Phake;

class TyphaxParserTest extends \Eloquent\Typhoon\Test\TestCase
{
  protected function setUp()
  {
    parent::setUp();

    $this->_typeRegistry = new TypeRegistry;
  }

  /**
   * @return array
   */
  public function parserData()
  {
    $data = array();
    
    // #0: simple type
    $expected = new NullType;
    $typhaxNode = new TyphaxType('null');
    $data[] = array($expected, $typhaxNode);

    // #1: type with attributes
    $expected = new ClassType(array(
      ClassType::ATTRIBUTE_INSTANCE_OF => 'foo',
      ClassType::ATTRIBUTE_AUTOLOAD => true,
    ));
    $typhaxNode = new TyphaxType('class');
    $typhaxNode->setAttribute(ClassType::ATTRIBUTE_INSTANCE_OF, 'foo');
    $typhaxNode->setAttribute(ClassType::ATTRIBUTE_AUTOLOAD, true);
    $data[] = array($expected, $typhaxNode);

    // #2: type with attributes and subtypes
    $expected = new TraversableType(array(
      TraversableType::ATTRIBUTE_INSTANCE_OF => 'foo',
    ));
    $expected->setTyphoonSubTypes(array(
      new StringType,
      new ClassType,
    ));
    $typhaxNode = new TyphaxType('traversable');
    $typhaxNode->setAttribute(TraversableType::ATTRIBUTE_INSTANCE_OF, 'foo');
    $typhaxNode->addSubType(new TyphaxType('string'));
    $typhaxNode->addSubType(new TyphaxType('class'));
    $data[] = array($expected, $typhaxNode);

    // #3: or composite
    $expected = new OrType;
    $expected->addTyphoonType(new StringType);
    $expected->addTyphoonType(new ClassType);
    $typhaxNode = new TyphaxComposite('|');
    $typhaxNode->addType(new TyphaxType('string'));
    $typhaxNode->addType(new TyphaxType('class'));
    $data[] = array($expected, $typhaxNode);

    // #3: and composite
    $expected = new AndType;
    $expected->addTyphoonType(new StringType);
    $expected->addTyphoonType(new ClassType);
    $typhaxNode = new TyphaxComposite('&');
    $typhaxNode->addType(new TyphaxType('string'));
    $typhaxNode->addType(new TyphaxType('class'));
    $data[] = array($expected, $typhaxNode);

    return $data;
  }

  /**
   * @covers Eloquent\Typhoon\Parser\TyphaxParser
   * @dataProvider parserData
   * @group parser
   */
  public function testParser(Type $expected, TyphaxNode $typhaxNode)
  {
    $parser = new TyphaxParser($this->_typeRegistry);

    $this->assertEquals($expected, $parser->parse($typhaxNode));
  }

  /**
   * @return array
   */
  public function parserFailureData()
  {
    $data = array();

    // #0: unsupported node
    $expected = __NAMESPACE__.'\Exception\UnsupportedTyphaxNodeException';
    $typhaxNode = Phake::mock('Eloquent\Typhax\AST\Node');
    $data[] = array($expected, $typhaxNode);

    // #1: unsupported composite
    $expected = __NAMESPACE__.'\Exception\UnsupportedTyphaxNodeException';
    $typhaxNode = new TyphaxComposite('~');
    $typhaxNode->addType(new TyphaxType('string'));
    $data[] = array($expected, $typhaxNode);

    // #2: unsupported type
    $expected = __NAMESPACE__.'\Exception\UnsupportedTyphaxNodeException';
    $typhaxNode = new TyphaxType('foo');
    $data[] = array($expected, $typhaxNode);

    // #3: attributes on a non-dynamic type
    $expected = __NAMESPACE__.'\Exception\UnsupportedTyphaxNodeException';
    $typhaxNode = new TyphaxType('null');
    $typhaxNode->setAttribute('foo', 'bar');
    $data[] = array($expected, $typhaxNode);

    // #4: sub-types on a type that is non-sub-typed
    $expected = __NAMESPACE__.'\Exception\UnsupportedTyphaxNodeException';
    $typhaxNode = new TyphaxType('string');
    $typhaxNode->addSubType(new TyphaxType('null'));
    $data[] = array($expected, $typhaxNode);

    return $data;
  }

  /**
   * @covers Eloquent\Typhoon\Parser\TyphaxParser
   * @dataProvider parserFailureData
   * @group parser
   */
  public function testParserFailure($expected, TyphaxNode $typhaxNode)
  {
    $parser = new TyphaxParser($this->_typeRegistry);

    $this->setExpectedException($expected);
    $parser->parse($typhaxNode);
  }

  /**
   * @var TypeRegistry
   */
  protected $_typeRegistry;
}
