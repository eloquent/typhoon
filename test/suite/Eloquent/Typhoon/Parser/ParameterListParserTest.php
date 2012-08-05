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

    public function testParseEmptyParameterList()
    {
        $source = <<<'EOD'
/**
     * Summary
     * Summary
     *
     * Description
     * Description
     */
EOD;
        $expected = new ParameterList;

        $this->assertEquals($expected, $this->_parser->parseBlockComment($source));
    }

    public function testVisitParameterList()
    {
        $source = <<<'EOD'
/**
     * Summary
     * Summary
     *
     * Description
     * Description
     *
     * @param string $foo This is the foo parameter.
     * @param integer $bar This is the bar parameter.
     * @param float|null $baz
     */
EOD;
        $fooType = new StringType;
        $fooParameter = new Parameter(
            'foo',
            $fooType,
            false,
            'This is the foo parameter.'
        );
        $barType = new IntegerType;
        $barParameter = new Parameter(
            'bar',
            $barType,
            false,
            'This is the bar parameter.'
        );
        $bazType = new OrType(array(
            new FloatType,
            new NullType,
        ));
        $bazParameter = new Parameter(
            'baz',
            $bazType
        );
        $expected = new ParameterList(array(
            $fooParameter,
            $barParameter,
            $bazParameter,
        ));
        $actual = $this->_parser->parseBlockComment($source);

        $this->assertEquals($expected, $actual);
        $this->assertInstanceOf(
            'Eloquent\Typhoon\Parameter\Parameter',
            $actual->parameterByName('baz')
        );
        $this->assertNull($actual->parameterByName('baz')->description());
    }

    public function testVisitParameterListVariableLength()
    {
        $source = <<<'EOD'
/**
     * Summary
     * Summary
     *
     * Description
     * Description
     *
     * @param string $foo,... This is the foo parameter.
     */
EOD;
        $fooType = new StringType;
        $fooParameter = new Parameter(
            'foo',
            $fooType,
            true,
            'This is the foo parameter.'
        );
        $expected = new ParameterList(
            array(
                $fooParameter,
            ),
            true
        );

        $this->assertEquals($expected, $this->_parser->parseBlockComment($source));
    }

    public function testVisitInvalidParameterTagFailureNoType()
    {
        $source = <<<'EOD'
/**
     * Summary
     * Summary
     *
     * Description
     * Description
     *
     * @param
     */
EOD;

        $this->setExpectedException(
            __NAMESPACE__.'\Exception\UnexpectedContentException',
            "Unexpected content at position 0. Expected 'type'."
        );
        $this->_parser->parseBlockComment($source);
    }

    public function testVisitInvalidParameterTagFailureNoName()
    {
        $source = <<<'EOD'
/**
     * Summary
     * Summary
     *
     * Description
     * Description
     *
     * @param string
     */
EOD;

        $this->setExpectedException(
            __NAMESPACE__.'\Exception\UnexpectedContentException',
            "Unexpected content at position 7. Expected 'name'."
        );
        $this->_parser->parseBlockComment($source);
    }
}
