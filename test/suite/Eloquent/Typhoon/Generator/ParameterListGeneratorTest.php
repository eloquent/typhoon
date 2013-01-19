<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Generator;

use Eloquent\Cosmos\ClassName;
use Eloquent\Typhax\Renderer\TypeRenderer;
use Eloquent\Typhax\Type\FloatType;
use Eloquent\Typhax\Type\IntegerType;
use Eloquent\Typhax\Type\StringType;
use Eloquent\Typhoon\Parameter\Parameter;
use Eloquent\Typhoon\Parameter\ParameterList;
use Eloquent\Typhoon\TestCase\MultiGenerationTestCase;
use Icecave\Pasta\AST\Func\ArrayTypeHint;
use Icecave\Pasta\AST\Func\Closure;
use Icecave\Pasta\AST\Func\Parameter as ParameterASTNode;
use Icecave\Pasta\AST\Identifier;
use Icecave\Rasta\Renderer;

class ParameterListGeneratorTest extends MultiGenerationTestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->_typeGenerator = new TyphaxASTGenerator;
        $this->_typeRenderer = new TypeRenderer;
        $this->_generator = new ParameterListGenerator(
            $this->_typeGenerator,
            $this->_typeRenderer
        );
        $this->_renderer = new Renderer;
    }

    protected function validatorFixture(ParameterList $parameterList)
    {
        $closure = new Closure;
        $closure->addParameter(new ParameterASTNode(
            new Identifier('arguments'),
            new ArrayTypeHint
        ));

        $expressions = $parameterList->accept($this->_generator);
        foreach ($expressions as $expression) {
            $closure->statementBlock()->add($expression);
        }

        $source = sprintf(
            '$check = %s;',
            $closure->accept($this->_renderer)
        );
        eval($source);

        return $check;
    }

    public function testConstructor()
    {
        $this->assertSame($this->_typeGenerator, $this->_generator->typeGenerator());
        $this->assertSame($this->_typeRenderer, $this->_generator->typeRenderer());
        $this->assertSame('\Typhoon', $this->_generator->validatorNamespace()->string());
    }

    public function testConstructorDefaults()
    {
        $this->_generator = new ParameterListGenerator;

        $this->assertInstanceOf(
            __NAMESPACE__.'\TyphaxASTGenerator',
            $this->_generator->typeGenerator()
        );
        $this->assertInstanceOf(
            'Eloquent\Typhax\Renderer\TypeRenderer',
            $this->_generator->typeRenderer()
        );
    }

    public function testSetValidatorNamespace()
    {
        $this->_generator->setValidatorNamespace(ClassName::fromString('\foo'));

        $this->assertSame('\foo', $this->_generator->validatorNamespace()->string());
    }

    public function testSetValidatorNamespaceNormalization()
    {
        $this->_generator->setValidatorNamespace(ClassName::fromString('foo'));

        $this->assertSame('\foo', $this->_generator->validatorNamespace()->string());
    }

    public function testVisitParameterListLogic()
    {
        $validatorFixture = $this->validatorFixture(new ParameterList(array(
            new Parameter(
                'foo',
                new StringType
            ),
            new Parameter(
                'bar',
                new IntegerType
            ),
            new Parameter(
                'baz',
                new FloatType
            ),
        )));

        $this->assertNull($validatorFixture(array('foo', 111, 1.11)));
        $this->assertNull($validatorFixture(array('bar', 222, 2.22)));
    }

    public function testVisitParameterListLogicOptional()
    {
        $validatorFixture = $this->validatorFixture(new ParameterList(array(
            new Parameter(
                'foo',
                new StringType
            ),
            new Parameter(
                'bar',
                new IntegerType,
                null,
                true
            ),
            new Parameter(
                'baz',
                new FloatType,
                null,
                true
            ),
        )));

        $this->assertNull($validatorFixture(array('foo')));
        $this->assertNull($validatorFixture(array('bar')));
    }

    public function testVisitParameterListLogicNoRequired()
    {
        $validatorFixture = $this->validatorFixture(new ParameterList(array(
            new Parameter(
                'foo',
                new StringType,
                null,
                true
            ),
            new Parameter(
                'bar',
                new IntegerType,
                null,
                true
            ),
            new Parameter(
                'baz',
                new FloatType,
                null,
                true
            ),
        )));

        $this->assertNull($validatorFixture(array('foo')));
        $this->assertNull($validatorFixture(array()));
    }

    public function testVisitParameterListLogicVariableLength()
    {
        $validatorFixture = $this->validatorFixture(new ParameterList(
            array(
                new Parameter(
                    'foo',
                    new StringType
                ),
                new Parameter(
                    'bar',
                    new IntegerType
                ),
                new Parameter(
                    'baz',
                    new FloatType,
                    null,
                    true
                ),
            ),
            true
        ));

        $this->assertNull($validatorFixture(array('foo', 111, 1.11, 2.22, 3.33, 4.44, 5.55)));
        $this->assertNull($validatorFixture(array('bar', 222, 6.66, 7.77, 8.88, 9.99)));
        $this->assertNull($validatorFixture(array('baz', 333)));
    }

    public function testVisitParameterListLogicEmpty()
    {
        $validatorFixture = $this->validatorFixture(new ParameterList);

        $this->assertNull($validatorFixture(array()));
        $this->assertNull($validatorFixture(array()));
    }

    public function parameterListLogicFailureData()
    {
        $data = array();

        $list = new ParameterList(array(
            new Parameter(
                'foo',
                new StringType
            ),
            new Parameter(
                'bar',
                new IntegerType
            ),
            new Parameter(
                'baz',
                new FloatType
            ),
        ));
        $arguments = array('foo', 111);
        $expected = 'Typhoon\Exception\MissingArgumentException';
        $expectedMessage = "Missing argument for parameter 'baz' at index 2. Expected 'float'.";
        $data['Not enough arguments 1'] =
            array($expected, $expectedMessage, $list, $arguments)
        ;

        $list = new ParameterList(array(
            new Parameter(
                'foo',
                new StringType
            ),
            new Parameter(
                'bar',
                new IntegerType
            ),
            new Parameter(
                'baz',
                new FloatType
            ),
        ));
        $arguments = array('foo');
        $expected = 'Typhoon\Exception\MissingArgumentException';
        $expectedMessage = "Missing argument for parameter 'bar' at index 1. Expected 'integer'.";
        $data['Not enough arguments 2'] =
            array($expected, $expectedMessage, $list, $arguments)
        ;

        $list = new ParameterList(array(
            new Parameter(
                'foo',
                new StringType
            ),
            new Parameter(
                'bar',
                new IntegerType
            ),
            new Parameter(
                'baz',
                new FloatType
            ),
        ));
        $arguments = array();
        $expected = 'Typhoon\Exception\MissingArgumentException';
        $expectedMessage = "Missing argument for parameter 'foo' at index 0. Expected 'string'.";
        $data['Not enough arguments 3'] =
            array($expected, $expectedMessage, $list, $arguments)
        ;

        $list = new ParameterList(array(
            new Parameter(
                'foo',
                new StringType
            ),
            new Parameter(
                'bar',
                new IntegerType
            ),
            new Parameter(
                'baz',
                new FloatType
            ),
        ));
        $arguments = array('foo', 111, 1.11, 2.22);
        $expected = 'Typhoon\Exception\UnexpectedArgumentException';
        $expectedMessage = "Unexpected argument of type 'float' at index 3.";
        $data['Too many arguments'] =
            array($expected, $expectedMessage, $list, $arguments)
        ;

        $list = new ParameterList(array(
            new Parameter(
                'foo',
                new StringType
            ),
            new Parameter(
                'bar',
                new IntegerType
            ),
            new Parameter(
                'baz',
                new FloatType
            ),
        ));
        $arguments = array('foo', 111, 'bar');
        $expected = 'Typhoon\Exception\UnexpectedArgumentValueException';
        $expectedMessage = "Unexpected argument of type 'string' for parameter 'baz' at index 2. Expected 'float'.";
        $data['Type mismatch 1'] =
            array($expected, $expectedMessage, $list, $arguments)
        ;

        $list = new ParameterList(array(
            new Parameter(
                'foo',
                new StringType
            ),
            new Parameter(
                'bar',
                new IntegerType
            ),
            new Parameter(
                'baz',
                new FloatType
            ),
        ));
        $arguments = array('foo', 'bar', 'baz');
        $expected = 'Typhoon\Exception\UnexpectedArgumentValueException';
        $expectedMessage = "Unexpected argument of type 'string' for parameter 'bar' at index 1. Expected 'integer'.";
        $data['Type mismatch 2'] =
            array($expected, $expectedMessage, $list, $arguments)
        ;

        $list = new ParameterList(array(
            new Parameter(
                'foo',
                new StringType
            ),
            new Parameter(
                'bar',
                new IntegerType
            ),
            new Parameter(
                'baz',
                new FloatType
            ),
        ));
        $arguments = array(111, 'bar', 'baz');
        $expected = 'Typhoon\Exception\UnexpectedArgumentValueException';
        $expectedMessage = "Unexpected argument of type 'integer' for parameter 'foo' at index 0. Expected 'string'.";
        $data['Type mismatch 3'] =
            array($expected, $expectedMessage, $list, $arguments)
        ;

        $list = new ParameterList(
            array(
                new Parameter(
                    'foo',
                    new StringType
                ),
                new Parameter(
                    'bar',
                    new IntegerType
                ),
                new Parameter(
                    'baz',
                    new FloatType,
                    null,
                    true
                ),
            ),
            true
        );
        $arguments = array('foo', 111, 1.11, 2.22, 'bar', 3.33);
        $expected = 'Typhoon\Exception\UnexpectedArgumentValueException';
        $expectedMessage = "Unexpected argument of type 'string' for parameter 'baz' at index 4. Expected 'float'.";
        $data['Variable length type mismatch'] =
            array($expected, $expectedMessage, $list, $arguments)
        ;

        return $data;
    }

    /**
     * @dataProvider parameterListLogicFailureData
     */
    public function testVisitParameterListLogicFailure(
        $expected,
        $expectedMessage,
        ParameterList $list,
        array $arguments
    ) {
        $validatorFixture = $this->validatorFixture($list);

        $this->setExpectedException($expected, $expectedMessage);
        $validatorFixture($arguments);
    }
}
