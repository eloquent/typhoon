<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Parser;

use Eloquent\Cosmos\ClassName;
use Eloquent\Typhax\Parser\Parser as TyphaxParser;
use Eloquent\Typhax\Type\ArrayType;
use Eloquent\Typhax\Type\CallableType;
use Eloquent\Typhax\Type\FloatType;
use Eloquent\Typhax\Type\IntegerType;
use Eloquent\Typhax\Type\MixedType;
use Eloquent\Typhax\Type\NullType;
use Eloquent\Typhax\Type\ObjectType;
use Eloquent\Typhax\Type\OrType;
use Eloquent\Typhax\Type\StringType;
use Eloquent\Typhax\Type\TraversableType;
use Eloquent\Typhoon\Parameter\ParameterList;
use Eloquent\Typhoon\Parameter\Parameter;
use Eloquent\Typhoon\TestCase\MultiGenerationTestCase;
use Phake;
use ReflectionClass;
use ReflectionMethod;
use stdClass;

class ParameterListParserTest extends MultiGenerationTestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->_parser = new ParameterListParser;
        $this->_className = ClassName::fromString('\foo');
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

        $this->assertEquals($expected, $this->_parser->parseBlockComment(
            $this->_className,
            'bar',
            $source
        ));
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
     * @param integer &$bar This is the bar parameter.
     * @param float|null $baz
     */
EOD;
        $fooType = new StringType;
        $fooParameter = new Parameter(
            'foo',
            $fooType,
            'This is the foo parameter.',
            false,
            false
        );
        $barType = new IntegerType;
        $barParameter = new Parameter(
            'bar',
            $barType,
            'This is the bar parameter.',
            false,
            true
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
        $actual = $this->_parser->parseBlockComment(
            $this->_className,
            'bar',
            $source
        );

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
            'This is the foo parameter.',
            true
        );
        $expected = new ParameterList(
            array(
                $fooParameter,
            ),
            true
        );

        $this->assertEquals($expected, $this->_parser->parseBlockComment(
            $this->_className,
            'bar',
            $source
        ));
    }

    public function testVisitParameterListVariableLengthNoDescription()
    {
        $source = <<<'EOD'
/**
     * Summary
     * Summary
     *
     * Description
     * Description
     *
     * @param string $foo,...
     */
EOD;
        $fooType = new StringType;
        $fooParameter = new Parameter(
            'foo',
            $fooType,
            null,
            true
        );
        $expected = new ParameterList(
            array(
                $fooParameter,
            ),
            true
        );

        $this->assertEquals($expected, $this->_parser->parseBlockComment(
            $this->_className,
            'bar',
            $source
        ));
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
            __NAMESPACE__.'\Exception\InvalidFunctionDocumentationException',
            'Invalid param tags found in the documentation for method \foo::bar().'
        );
        $this->_parser->parseBlockComment(
            $this->_className,
            'bar',
            $source
        );
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
            __NAMESPACE__.'\Exception\InvalidFunctionDocumentationException',
            'Invalid param tags found in the documentation for method \foo::bar().'
        );
        $this->_parser->parseBlockComment(
            $this->_className,
            'bar',
            $source
        );
    }

    protected function typicalMethod(
        $foo,
        array $bar,
        stdClass $baz,
        &$qux,
        $doom = 1,
        array $splat = array(),
        array $ping = null,
        stdClass $pong = null,
        &$pang = 'peng',
        $pung = null
    ) {
    }

    public function testFromReflector()
    {
        $reflector = new ReflectionMethod($this, 'typicalMethod');
        $expected = new ParameterList(array(
            new Parameter(
                'foo',
                new MixedType,
                null,
                false,
                false
            ),
            new Parameter(
                'bar',
                new TraversableType(
                    new ArrayType,
                    new MixedType,
                    new MixedType
                ),
                null,
                false,
                false
            ),
            new Parameter(
                'baz',
                new ObjectType(ClassName::fromString('\stdClass')),
                null,
                false,
                false
            ),
            new Parameter(
                'qux',
                new MixedType,
                null,
                false,
                true
            ),
            new Parameter(
                'doom',
                new MixedType,
                null,
                true,
                false
            ),
            new Parameter(
            'splat',
                new TraversableType(
                    new ArrayType,
                    new MixedType,
                    new MixedType
                ),
                null,
                true,
                false
            ),
            new Parameter(
                'ping',
                new OrType(array(
                    new TraversableType(
                        new ArrayType,
                        new MixedType,
                        new MixedType
                    ),
                    new NullType,
                )),
                null,
                true,
                false
            ),
            new Parameter(
                'pong',
                new OrType(array(
                    new ObjectType(ClassName::fromString('\stdClass')),
                    new NullType,
                )),
                null,
                true,
                false
            ),
            new Parameter(
                'pang',
                new MixedType,
                null,
                true,
                true
            ),
            new Parameter(
                'pung',
                new MixedType,
                null,
                true,
                false
            ),
        ));

        $this->assertEquals($expected, $this->_parser->parseReflector($reflector));
    }

    public function testFromReflectorCallable()
    {
        $reflectorReflector = new ReflectionClass('ReflectionParameter');
        if (!$reflectorReflector->hasMethod('isCallable')) {
            $this->markTestSkipped('Requires ReflectionParameter::isCallable().');
        }

        $reflector = new ReflectionMethod(
            'Eloquent\Typhoon\TestFixture\PHP54Features',
            'methodWithCallableTypeHints'
        );
        $expected = new ParameterList(array(
            new Parameter(
                'foo',
                new CallableType,
                null,
                false,
                false
            ),
            new Parameter(
                'bar',
                new OrType(array(
                    new CallableType,
                    new NullType,
                )),
                null,
                true,
                false
            ),
        ));

        $this->assertEquals($expected, $this->_parser->parseReflector($reflector));
    }

    public function testParseParameterReflectorFailureUndefinedClass()
    {
        $classReflector = new ReflectionClass(__CLASS__);
        $methodReflector = new ReflectionMethod(__METHOD__);
        $parameterReflector = Phake::mock('ReflectionParameter');
        Phake::when($parameterReflector)
            ->getDeclaringClass(Phake::anyParameters())
            ->thenReturn($classReflector)
        ;
        Phake::when($parameterReflector)
            ->getDeclaringFunction(Phake::anyParameters())
            ->thenReturn($methodReflector)
        ;
        Phake::when($parameterReflector)
            ->getName(Phake::anyParameters())
            ->thenReturn('foo')
        ;
        Phake::when($parameterReflector)
            ->__toString(Phake::anyParameters())
            ->thenReturn('Parameter #0 [ <required> bar\baz $qux ]')
        ;
        Phake::when($parameterReflector)
            ->getClass(Phake::anyParameters())
            ->thenThrow(Phake::mock('ReflectionException'))
        ;

        $this->setExpectedException(
            __NAMESPACE__.'\Exception\TypeHintUndefinedClassException',
            sprintf(
                'Unable to resolve type hint of \bar\baz for parameter $foo in method \%s::%s().',
                __CLASS__,
                __FUNCTION__
            )
        );
        $this->_parser->parseParameterReflector($parameterReflector);
    }
}
