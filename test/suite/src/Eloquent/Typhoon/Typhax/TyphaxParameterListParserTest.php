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

use Eloquent\Typhoon\Documentation\AST\DocumentationBlock;
use Eloquent\Typhoon\Documentation\AST\DocumentationTag;
use Eloquent\Typhoon\Documentation\AST\DocumentationTags;
use Eloquent\Typhoon\Parameter\Parameter;
use Eloquent\Typhoon\Parameter\ParameterList\ParameterList;
use Eloquent\Typhoon\Primitive\String;
use Eloquent\Typhoon\Type\Registry\TypeRegistry;
use Eloquent\Typhoon\Type\StringType;
use Eloquent\Typhoon\Type\Type;
use Phake;

/**
 * @covers Eloquent\Typhoon\Typhax\TyphaxParameterListParser
 * @covers Eloquent\Typhoon\Parameter\ParameterList\ParameterListParser
 */
class TyphaxParameterListParserTest extends \Eloquent\Typhoon\Test\TestCase
{
  protected function setUp()
  {
    parent::setUp();

    $this->_typeRegistry = new TypeRegistry;
    $this->_typhaxTranscompiler = new TyphaxTranscompiler($this->_typeRegistry);
    $this->_parser = new TyphaxParameterListParser($this->_typhaxTranscompiler);
  }

  /**
   * @param string $content
   *
   * @return DocumentationTag
   */
  protected function parameterTagFixture($content)
  {
    return new DocumentationTag(
      new String(TyphaxParameterListParser::TAG_PARAMETER)
      , new String($content)
    );
  }
  /**
   * @param string $name
   * @param Type $type
   *
   * @return Parameter
   */
  protected function parameterFixture($name, Type $type)
  {
    $parameter = new Parameter;
    $parameter->setName(new String($name));
    $parameter->setType($type);

    return $parameter;
  }

  public function testConstructor()
  {
    $this->assertSame($this->_typhaxTranscompiler, $this->_parser->typhaxTranscompiler());
    $this->assertInstanceOf('Eloquent\Typhax\Lexer\Lexer', $this->_parser->typhaxLexer());
    $this->assertInstanceOf('Eloquent\Typhax\Parser\Parser', $this->_parser->typhaxParser());


    $typhaxParser = Phake::mock('Eloquent\Typhax\Parser\Parser');
    $typhaxLexer = Phake::mock('Eloquent\Typhax\Lexer\Lexer');
    $this->_parser = new TyphaxParameterListParser($this->_typhaxTranscompiler, $typhaxLexer, $typhaxParser);

    $this->assertSame($this->_typhaxTranscompiler, $this->_parser->typhaxTranscompiler());
    $this->assertSame($typhaxLexer, $this->_parser->typhaxLexer());
    $this->assertSame($typhaxParser, $this->_parser->typhaxParser());
  }

  public function parserData()
  {
    $data = array();

    // #0: empty parameter list
    $documentationBlock = new DocumentationBlock;
    $expected = new ParameterList;
    $data[] = array($expected, $documentationBlock);

    // #1: basic parameter list
    $documentationTags = new DocumentationTags(array(
      $this->parameterTagFixture('string $foo'),
    ));
    $documentationBlock = new DocumentationBlock($documentationTags);
    $expected = new ParameterList;
    $expected[] = $this->parameterFixture('foo', new StringType);
    $data[] = array($expected, $documentationBlock);

    return $data;
  }

  /**
   * @dataProvider parserData
   */
  public function testParser(ParameterList $expected, DocumentationBlock $documentationBlock)
  {
    $this->assertEquals($expected, $this->_parser->parseDocumentationBlock($documentationBlock));
  }

  public function parserFailureData()
  {
    $data = array();

    // #0: invalid parameter specification
    $documentationTags = new DocumentationTags(array(
      $this->parameterTagFixture('string'),
    ));
    $documentationBlock = new DocumentationBlock($documentationTags);
    $expected = 'Eloquent\Typhoon\Typhax\Exception\InvalidParameterTagException';
    $data[] = array($expected, $documentationBlock);

    // #1: invalid parameter specification
    $documentationTags = new DocumentationTags(array(
      $this->parameterTagFixture('$foo'),
    ));
    $documentationBlock = new DocumentationBlock($documentationTags);
    $expected = 'Eloquent\Typhoon\Typhax\Exception\InvalidParameterTagException';
    $data[] = array($expected, $documentationBlock);

    return $data;
  }

  /**
   * @dataProvider parserFailureData
   */
  public function testParserFailure($expected, DocumentationBlock $documentationBlock)
  {
    $this->setExpectedException($expected);
    $this->_parser->parseDocumentationBlock($documentationBlock);
  }
}
