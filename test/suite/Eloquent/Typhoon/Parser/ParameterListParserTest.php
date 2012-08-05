<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Parser;

use Eloquent\Blox\AST\DocumentationBlock;
use Eloquent\Blox\AST\DocumentationTag;
use Eloquent\Typhax\Parser\Parser as TyphaxParser;
use Eloquent\Typhax\Type\FloatType;
use Eloquent\Typhax\Type\IntegerType;
use Eloquent\Typhax\Type\NullType;
use Eloquent\Typhax\Type\OrType;
use Eloquent\Typhax\Type\StringType;
use Eloquent\Typhoon\Parameter\ParameterList;
use Eloquent\Typhoon\Parameter\Parameter;
use PHPUnit_Framework_TestCase;

class ParameterListParserTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->_parser = new ParameterListParser;
    }

    public function testConstructor()
    {
        $typhaxParser = new TyphaxParser;
        $parser = new ParameterListParser(
            $typhaxParser
        );

        $this->assertSame($typhaxParser, $parser->typhaxParser());
    }

    public function testConstructorDefaults()
    {
        $this->assertInstanceOf(
            'Eloquent\Typhax\Parser\Parser',
            $this->_parser->typhaxParser()
        );
    }

    public function testVisitEmptyParameterList()
    {
        $block = new DocumentationBlock;
        $expected = new ParameterList;

        $this->assertEquals($expected, $block->accept($this->_parser));
    }

    public function testVisitParameterList()
    {
        $fooTag = new DocumentationTag('param', 'string $foo This is the foo parameter.');
        $barTag = new DocumentationTag('param', 'integer $bar This is the bar parameter.');
        $bazTag = new DocumentationTag('param', 'float|null $baz');
        $block = new DocumentationBlock(array(
            $fooTag,
            $barTag,
            $bazTag,
        ));
        $fooType = new StringType;
        $fooParameter = new Parameter($fooType, 'foo', 'This is the foo parameter.');
        $barType = new IntegerType;
        $barParameter = new Parameter($barType, 'bar', 'This is the bar parameter.');
        $bazType = new OrType(array(
            new FloatType,
            new NullType,
        ));
        $bazParameter = new Parameter($bazType, 'baz');
        $expected = new ParameterList(array(
            $fooParameter,
            $barParameter,
            $bazParameter,
        ));
        $actual = $block->accept($this->_parser);

        $this->assertEquals($expected, $actual);
        $this->assertInstanceOf(
            'Eloquent\Typhoon\Parameter\Parameter',
            $actual->parameterByName('baz')
        );
        $this->assertNull($actual->parameterByName('baz')->description());
    }

    public function testVisitParameterListVariableLength()
    {
        $fooTag = new DocumentationTag('param', 'string $foo,... This is the foo parameter.');
        $block = new DocumentationBlock(array(
            $fooTag,
        ));
        $fooType = new StringType;
        $fooParameter = new Parameter($fooType, 'foo', 'This is the foo parameter.');
        $expected = new ParameterList(
            array(
                $fooParameter,
            ),
            true
        );

        $this->assertEquals($expected, $block->accept($this->_parser));
    }

    public function testVisitInvalidParameterTagFailureNoType()
    {
        $tag = new DocumentationTag('param', ' ');

        $this->setExpectedException(
            __NAMESPACE__.'\Exception\UnexpectedContentException',
            "Unexpected content at position 2. Expected 'type'."
        );
        $tag->accept($this->_parser);
    }

    public function testVisitInvalidParameterTagFailureNoName()
    {
        $tag = new DocumentationTag('param', 'string ');

        $this->setExpectedException(
            __NAMESPACE__.'\Exception\UnexpectedContentException',
            "Unexpected content at position 8. Expected 'name'."
        );
        $tag->accept($this->_parser);
    }
}
