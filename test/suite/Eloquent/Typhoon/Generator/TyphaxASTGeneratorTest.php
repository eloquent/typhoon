<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Generator;

use ArrayIterator;
use Eloquent\Cosmos\ClassName;
use Eloquent\Typhax\Type\AndType;
use Eloquent\Typhax\Type\ArrayType;
use Eloquent\Typhax\Type\BooleanType;
use Eloquent\Typhax\Type\CallableType;
use Eloquent\Typhax\Type\ExtensionType;
use Eloquent\Typhax\Type\FloatType;
use Eloquent\Typhax\Type\IntegerType;
use Eloquent\Typhax\Type\MixedType;
use Eloquent\Typhax\Type\NullType;
use Eloquent\Typhax\Type\NumericType;
use Eloquent\Typhax\Type\ObjectType;
use Eloquent\Typhax\Type\OrType;
use Eloquent\Typhax\Type\ResourceType;
use Eloquent\Typhax\Type\StreamType;
use Eloquent\Typhax\Type\StringType;
use Eloquent\Typhax\Type\StringableType;
use Eloquent\Typhax\Type\TraversableType;
use Eloquent\Typhax\Type\TupleType;
use Eloquent\Typhax\Type\Type;
use Eloquent\Typhax\Type\Visitor;
use Eloquent\Typhoon\TestCase\MultiGenerationTestCase;
use Icecave\Pasta\AST\Expr\Call;
use Icecave\Pasta\AST\Expr\Variable;
use Icecave\Pasta\AST\Func\Closure;
use Icecave\Pasta\AST\Func\Parameter;
use Icecave\Pasta\AST\Identifier;
use Icecave\Pasta\AST\Stmt\ReturnStatement;
use Icecave\Rasta\Renderer;
use Phake;
use stdClass;

class TyphaxASTGeneratorTest extends MultiGenerationTestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->_loader = Phake::mock('Eloquent\Typhoon\Extension\ExtensionLoaderInterface');
        $this->_generator = new TyphaxASTGenerator(null, $this->_loader);
        $this->_renderer = new Renderer;
    }

    protected function validatorFixture(Type $type)
    {
        $expression = $type->accept($this->_generator);
        if (null === $expression) {
            return function () {
                return true;
            };
        }

        if ($expression instanceof Closure) {
            $closure = $expression;
        } else {
            $closure = new Closure;
            $closure->addParameter(new Parameter(
                new Identifier('value')
            ));
            $closure->statementBlock()->add(
                new ReturnStatement($expression)
            );
        }

        $source = sprintf(
            '$check = %s;',
            $closure->accept($this->_renderer)
        );
        eval($source);

        return $check;
    }

    protected function streamFixture($mode)
    {
        $this->_files[] = $file = $path = sys_get_temp_dir().'/'.uniqid('typhoon-');
        touch($file);
        $this->_streams[] = $stream = fopen($file, $mode);

        return $stream;
    }

    public function testVisitAndTypeLogic()
    {
        $validator = $this->validatorFixture(new AndType(array(
            new ObjectType(ClassName::fromString('Eloquent\Typhax\Type\Visitor')),
            new ObjectType(ClassName::fromString('Phake_IMock')),
        )));

        $this->assertTrue($validator(Phake::mock('Eloquent\Typhax\Type\Visitor')));

        $this->assertFalse($validator(null));
        $this->assertFalse($validator(true));
        $this->assertFalse($validator(false));
        $this->assertFalse($validator(111));
        $this->assertFalse($validator(1.11));
        $this->assertFalse($validator('foo'));
        $this->assertFalse($validator(array()));
        $this->assertFalse($validator(new stdClass));
        $this->assertFalse($validator($this->_generator));
        $this->assertFalse($validator(stream_context_create()));
    }

    /**
     * @link https://github.com/eloquent/typhoon/issues/85
     */
    public function testVisitAndTypeLogicWithClosureBasedSubCheck()
    {
        $validator = $this->validatorFixture(new AndType(array(
            new StringableType,
            new NullType,
        )));

        $this->assertFalse($validator('foo'));
        $this->assertFalse($validator(111));
        $this->assertFalse($validator(1.11));
        $this->assertFalse($validator(Phake::mock('SplFileInfo')));

        $this->assertFalse($validator(true));
        $this->assertFalse($validator(false));
        $this->assertFalse($validator(array()));
        $this->assertFalse($validator(new stdClass));
        $this->assertFalse($validator(stream_context_create()));

        $this->assertFalse($validator(null));
    }

    public function testVisitArrayTypeLogic()
    {
        $validator = $this->validatorFixture(new ArrayType);

        $this->assertTrue($validator(array()));
        $this->assertTrue($validator(array('foo')));
        $this->assertTrue($validator(array('foo' => 'bar')));

        $this->assertFalse($validator(null));
        $this->assertFalse($validator(true));
        $this->assertFalse($validator(false));
        $this->assertFalse($validator(111));
        $this->assertFalse($validator(1.11));
        $this->assertFalse($validator('foo'));
        $this->assertFalse($validator(new stdClass));
        $this->assertFalse($validator(stream_context_create()));
    }

    public function testVisitBooleanTypeLogic()
    {
        $validator = $this->validatorFixture(new BooleanType);

        $this->assertTrue($validator(true));
        $this->assertTrue($validator(false));

        $this->assertFalse($validator(null));
        $this->assertFalse($validator(111));
        $this->assertFalse($validator(1.11));
        $this->assertFalse($validator('foo'));
        $this->assertFalse($validator(array()));
        $this->assertFalse($validator(new stdClass));
        $this->assertFalse($validator(stream_context_create()));
    }

    public function testVisitCallableTypeLogic()
    {
        $validator = $this->validatorFixture(new CallableType);

        $this->assertTrue($validator(function() {}));
        $this->assertTrue($validator('substr'));

        $this->assertFalse($validator(true));
        $this->assertFalse($validator(false));
        $this->assertFalse($validator(null));
        $this->assertFalse($validator(111));
        $this->assertFalse($validator(1.11));
        $this->assertFalse($validator('foo'));
        $this->assertFalse($validator(array()));
        $this->assertFalse($validator(new stdClass));
        $this->assertFalse($validator(stream_context_create()));
    }

    public function testVisitExtensionType()
    {
        $closure   = Phake::mock('Icecave\Pasta\AST\Func\Closure');
        $extension = Phake::mock('Eloquent\Typhoon\Extension\ExtensionInterface');
        $className = ClassName::fromString(get_class($extension));

        $type = new ExtensionType(
            $className,
            array(),
            array()
        );

        Phake::when($this->_loader)
            ->load($className->string())
            ->thenReturn($extension);

        Phake::when($extension)
            ->generateTypeCheck($this->_generator, $type)
            ->thenReturn($closure);

        $expected = new Call($closure);
        $expected->add(new Variable(new Identifier('value')));

        $result = $this->_generator->visitExtensionType($type);

        $this->assertEquals($expected, $result);
    }

    public function testVisitFloatTypeLogic()
    {
        $validator = $this->validatorFixture(new FloatType);

        $this->assertTrue($validator(1.11));

        $this->assertFalse($validator(true));
        $this->assertFalse($validator(false));
        $this->assertFalse($validator(null));
        $this->assertFalse($validator(111));
        $this->assertFalse($validator('foo'));
        $this->assertFalse($validator(array()));
        $this->assertFalse($validator(new stdClass));
        $this->assertFalse($validator(stream_context_create()));
    }

    public function testVisitIntegerTypeLogic()
    {
        $validator = $this->validatorFixture(new IntegerType);

        $this->assertTrue($validator(111));

        $this->assertFalse($validator(true));
        $this->assertFalse($validator(false));
        $this->assertFalse($validator(null));
        $this->assertFalse($validator(1.11));
        $this->assertFalse($validator('foo'));
        $this->assertFalse($validator(array()));
        $this->assertFalse($validator(new stdClass));
        $this->assertFalse($validator(stream_context_create()));
    }

    public function testVisitMixedTypeLogic()
    {
        $validator = $this->validatorFixture(new MixedType);

        $this->assertTrue($validator(true));
        $this->assertTrue($validator(false));
        $this->assertTrue($validator(null));
        $this->assertTrue($validator(111));
        $this->assertTrue($validator(1.11));
        $this->assertTrue($validator('foo'));
        $this->assertTrue($validator(array()));
        $this->assertTrue($validator(new stdClass));
        $this->assertTrue($validator(stream_context_create()));
    }

    public function testVisitNullTypeLogic()
    {
        $validator = $this->validatorFixture(new NullType);

        $this->assertTrue($validator(null));

        $this->assertFalse($validator(true));
        $this->assertFalse($validator(false));
        $this->assertFalse($validator(111));
        $this->assertFalse($validator(1.11));
        $this->assertFalse($validator('foo'));
        $this->assertFalse($validator(array()));
        $this->assertFalse($validator(new stdClass));
        $this->assertFalse($validator(stream_context_create()));
    }

    public function testVisitNumericTypeLogic()
    {
        $validator = $this->validatorFixture(new NumericType);

        $this->assertTrue($validator(111));
        $this->assertTrue($validator(1.11));
        $this->assertTrue($validator('111'));
        $this->assertTrue($validator('1.11'));
        $this->assertTrue($validator('+0123.45e6'));
        $this->assertTrue($validator('0xFF'));

        $this->assertFalse($validator(true));
        $this->assertFalse($validator(false));
        $this->assertFalse($validator(null));
        $this->assertFalse($validator('foo'));
        $this->assertFalse($validator(array()));
        $this->assertFalse($validator(new stdClass));
        $this->assertFalse($validator(stream_context_create()));
    }

    public function testVisitObjectTypeLogic()
    {
        $validator = $this->validatorFixture(new ObjectType);

        $this->assertTrue($validator(new stdClass));

        $this->assertFalse($validator(true));
        $this->assertFalse($validator(false));
        $this->assertFalse($validator(null));
        $this->assertFalse($validator(111));
        $this->assertFalse($validator(1.11));
        $this->assertFalse($validator('foo'));
        $this->assertFalse($validator(array()));
        $this->assertFalse($validator(stream_context_create()));
    }

    public function testVisitObjectOfTypeClassLogic()
    {
        $validator = $this->validatorFixture(new ObjectType(
            ClassName::fromString(__NAMESPACE__.'\TyphaxASTGenerator')
        ));

        $this->assertTrue($validator($this->_generator));

        $this->assertFalse($validator(true));
        $this->assertFalse($validator(false));
        $this->assertFalse($validator(null));
        $this->assertFalse($validator(111));
        $this->assertFalse($validator(1.11));
        $this->assertFalse($validator('foo'));
        $this->assertFalse($validator(array()));
        $this->assertFalse($validator(new stdClass));
        $this->assertFalse($validator(stream_context_create()));
    }

    public function testVisitObjectOfTypeInterfaceLogic()
    {
        $validator = $this->validatorFixture(new ObjectType(
            ClassName::fromString('Eloquent\Typhax\Type\Visitor')
        ));

        $this->assertTrue($validator($this->_generator));

        $this->assertFalse($validator(true));
        $this->assertFalse($validator(false));
        $this->assertFalse($validator(null));
        $this->assertFalse($validator(111));
        $this->assertFalse($validator(1.11));
        $this->assertFalse($validator('foo'));
        $this->assertFalse($validator(array()));
        $this->assertFalse($validator(new stdClass));
        $this->assertFalse($validator(stream_context_create()));
    }

    public function testVisitOrTypeLogic()
    {
        $validator = $this->validatorFixture(new OrType(array(
            new ObjectType(ClassName::fromString('Eloquent\Typhax\Type\Visitor')),
            new ObjectType(ClassName::fromString('Phake_IMock')),
        )));

        $this->assertTrue($validator($this->_generator));
        $this->assertTrue($validator(Phake::mock('Eloquent\Typhax\Type\Visitor')));
        $this->assertTrue($validator(Phake::mock('stdClass')));

        $this->assertFalse($validator(null));
        $this->assertFalse($validator(true));
        $this->assertFalse($validator(false));
        $this->assertFalse($validator(111));
        $this->assertFalse($validator(1.11));
        $this->assertFalse($validator('foo'));
        $this->assertFalse($validator(array()));
        $this->assertFalse($validator(new stdClass));
        $this->assertFalse($validator(stream_context_create()));
    }

    /**
     * @link https://github.com/eloquent/typhoon/issues/81
     */
    public function testVisitOrTypeLogicWithClosureBasedSubCheck()
    {
        $validator = $this->validatorFixture(new OrType(array(
            new StringableType,
            new NullType,
        )));

        $this->assertTrue($validator('foo'));
        $this->assertTrue($validator(111));
        $this->assertTrue($validator(1.11));
        $this->assertTrue($validator(Phake::mock('SplFileInfo')));

        $this->assertFalse($validator(true));
        $this->assertFalse($validator(false));
        $this->assertFalse($validator(array()));
        $this->assertFalse($validator(new stdClass));
        $this->assertFalse($validator(stream_context_create()));

        $this->assertTrue($validator(null));
    }

    public function testVisitResourceTypeLogic()
    {
        $validator = $this->validatorFixture(new ResourceType);

        $this->assertTrue($validator(stream_context_create()));

        $this->assertFalse($validator(true));
        $this->assertFalse($validator(false));
        $this->assertFalse($validator(null));
        $this->assertFalse($validator(111));
        $this->assertFalse($validator(1.11));
        $this->assertFalse($validator('foo'));
        $this->assertFalse($validator(array()));
        $this->assertFalse($validator(new stdClass));
    }

    public function testVisitResourceTypeOfTypeLogic()
    {
        $file = fopen(__FILE__, 'rb');
        $resourceType = get_resource_type($file);
        $validator = $this->validatorFixture(new ResourceType($resourceType));
        $actual = $validator($file);
        fclose($file);

        $this->assertTrue($actual);

        $this->assertFalse($validator(true));
        $this->assertFalse($validator(false));
        $this->assertFalse($validator(null));
        $this->assertFalse($validator(111));
        $this->assertFalse($validator(1.11));
        $this->assertFalse($validator('foo'));
        $this->assertFalse($validator(array()));
        $this->assertFalse($validator(new stdClass));
        $this->assertFalse($validator(stream_context_create()));
    }

    public function testVisitStreamTypeLogic()
    {
        $validator = $this->validatorFixture(new StreamType);
        $stream = $this->streamFixture('rb');

        $this->assertTrue($validator($stream));

        $this->assertFalse($validator(true));
        $this->assertFalse($validator(false));
        $this->assertFalse($validator(null));
        $this->assertFalse($validator(111));
        $this->assertFalse($validator(1.11));
        $this->assertFalse($validator('foo'));
        $this->assertFalse($validator(array()));
        $this->assertFalse($validator(new stdClass));
        $this->assertFalse($validator(stream_context_create()));
    }

    public function testVisitStreamTypeReadableLogic()
    {
        $validator = $this->validatorFixture(new StreamType(true));

        $readableStream = $this->streamFixture('rb');
        $nonReadableStream = $this->streamFixture('wb');

        $this->assertTrue($validator($readableStream));

        $this->assertFalse($validator($nonReadableStream));
        $this->assertFalse($validator(true));
        $this->assertFalse($validator(false));
        $this->assertFalse($validator(null));
        $this->assertFalse($validator(111));
        $this->assertFalse($validator(1.11));
        $this->assertFalse($validator('foo'));
        $this->assertFalse($validator(array()));
        $this->assertFalse($validator(new stdClass));
        $this->assertFalse($validator(stream_context_create()));
    }

    public function testVisitStreamTypeNotReadableLogic()
    {
        $validator = $this->validatorFixture(new StreamType(false));

        $readableStream = $this->streamFixture('rb');
        $nonReadableStream = $this->streamFixture('wb');

        $this->assertTrue($validator($nonReadableStream));

        $this->assertFalse($validator($readableStream));
        $this->assertFalse($validator(true));
        $this->assertFalse($validator(false));
        $this->assertFalse($validator(null));
        $this->assertFalse($validator(111));
        $this->assertFalse($validator(1.11));
        $this->assertFalse($validator('foo'));
        $this->assertFalse($validator(array()));
        $this->assertFalse($validator(new stdClass));
        $this->assertFalse($validator(stream_context_create()));
    }

    public function testVisitStreamTypeWritableLogic()
    {
        $validator = $this->validatorFixture(new StreamType(null, true));

        $writableStream = $this->streamFixture('wb');
        $nonWritableStream = $this->streamFixture('rb');

        $this->assertTrue($validator($writableStream));

        $this->assertFalse($validator($nonWritableStream));
        $this->assertFalse($validator(true));
        $this->assertFalse($validator(false));
        $this->assertFalse($validator(null));
        $this->assertFalse($validator(111));
        $this->assertFalse($validator(1.11));
        $this->assertFalse($validator('foo'));
        $this->assertFalse($validator(array()));
        $this->assertFalse($validator(new stdClass));
        $this->assertFalse($validator(stream_context_create()));
    }

    public function testVisitStreamTypeNotWritableLogic()
    {
        $validator = $this->validatorFixture(new StreamType(null, false));

        $writableStream = $this->streamFixture('wb');
        $nonWritableStream = $this->streamFixture('rb');

        $this->assertTrue($validator($nonWritableStream));

        $this->assertFalse($validator($writableStream));
        $this->assertFalse($validator(true));
        $this->assertFalse($validator(false));
        $this->assertFalse($validator(null));
        $this->assertFalse($validator(111));
        $this->assertFalse($validator(1.11));
        $this->assertFalse($validator('foo'));
        $this->assertFalse($validator(array()));
        $this->assertFalse($validator(new stdClass));
        $this->assertFalse($validator(stream_context_create()));
    }

    public function testVisitStreamTypeReadWriteLogic()
    {
        $validator = $this->validatorFixture(new StreamType(true, true));

        $readWriteStream = $this->streamFixture('r+');
        $nonReadableStream = $this->streamFixture('wb');
        $nonWritableStream = $this->streamFixture('rb');

        $this->assertTrue($validator($readWriteStream));

        $this->assertFalse($validator($nonReadableStream));
        $this->assertFalse($validator($nonWritableStream));
        $this->assertFalse($validator(true));
        $this->assertFalse($validator(false));
        $this->assertFalse($validator(null));
        $this->assertFalse($validator(111));
        $this->assertFalse($validator(1.11));
        $this->assertFalse($validator('foo'));
        $this->assertFalse($validator(array()));
        $this->assertFalse($validator(new stdClass));
        $this->assertFalse($validator(stream_context_create()));
    }

    public function testVisitStringTypeLogic()
    {
        $validator = $this->validatorFixture(new StringType);

        $this->assertTrue($validator('foo'));

        $this->assertFalse($validator(true));
        $this->assertFalse($validator(false));
        $this->assertFalse($validator(null));
        $this->assertFalse($validator(111));
        $this->assertFalse($validator(1.11));
        $this->assertFalse($validator(array()));
        $this->assertFalse($validator(new stdClass));
        $this->assertFalse($validator(stream_context_create()));
    }

    public function testVisitStringableTypeLogic()
    {
        $validator = $this->validatorFixture(new StringableType);

        $this->assertTrue($validator('foo'));
        $this->assertTrue($validator(111));
        $this->assertTrue($validator(1.11));
        $this->assertTrue($validator(Phake::mock('SplFileInfo')));

        $this->assertFalse($validator(true));
        $this->assertFalse($validator(false));
        $this->assertFalse($validator(null));
        $this->assertFalse($validator(array()));
        $this->assertFalse($validator(new stdClass));
        $this->assertFalse($validator(stream_context_create()));
    }

    public function testVisitTraversableTypeLogicArrayPrimary()
    {
        $validator = $this->validatorFixture(new TraversableType(
            new ArrayType,
            new StringType,
            new IntegerType
        ));
        $validArray = array(
            'foo' => 111,
            'bar' => 222,
        );
        $partiallyValidValues = array(
            'foo' => 111,
            'bar' => 2.22,
        );
        $partiallyValidKeys = array(
            'foo' => 111,
            222 => 333,
        );
        $invalidPrimaryType = new ArrayIterator(array(
            'foo' => 111,
            'bar' => 222,
        ));

        $this->assertTrue($validator($validArray));
        $this->assertTrue($validator(array()));

        $this->assertFalse($validator($partiallyValidValues));
        $this->assertFalse($validator($partiallyValidKeys));
        $this->assertFalse($validator($invalidPrimaryType));
        $this->assertFalse($validator(null));
        $this->assertFalse($validator(true));
        $this->assertFalse($validator(false));
        $this->assertFalse($validator(111));
        $this->assertFalse($validator(1.11));
        $this->assertFalse($validator('foo'));
        $this->assertFalse($validator(new stdClass));
        $this->assertFalse($validator(stream_context_create()));
    }

    public function testVisitTraversableTypeLogicObjectPrimary()
    {
        $validator = $this->validatorFixture(new TraversableType(
            new ObjectType(ClassName::fromString('ArrayIterator')),
            new StringType,
            new IntegerType
        ));
        $validObject = new ArrayIterator(array(
            'foo' => 111,
            'bar' => 222,
        ));
        $partiallyValidValues = new ArrayIterator(array(
            'foo' => 111,
            'bar' => 2.22,
        ));
        $partiallyValidKeys = new ArrayIterator(array(
            'foo' => 111,
            222 => 333,
        ));
        $invalidPrimaryType = array(
            'foo' => 111,
            'bar' => 222,
        );

        $this->assertTrue($validator($validObject));
        $this->assertTrue($validator(new ArrayIterator));

        $this->assertFalse($validator($partiallyValidValues));
        $this->assertFalse($validator($partiallyValidKeys));
        $this->assertFalse($validator($invalidPrimaryType));
        $this->assertFalse($validator(null));
        $this->assertFalse($validator(true));
        $this->assertFalse($validator(false));
        $this->assertFalse($validator(111));
        $this->assertFalse($validator(1.11));
        $this->assertFalse($validator('foo'));
        $this->assertFalse($validator(new stdClass));
        $this->assertFalse($validator(stream_context_create()));
    }

    public function testVisitTraversableTypeLogicMixedPrimary()
    {
        $validator = $this->validatorFixture(new TraversableType(
            new MixedType,
            new StringType,
            new IntegerType
        ));
        $validArray = array(
            'foo' => 111,
            'bar' => 222,
        );
        $validObject = new ArrayIterator(array(
            'foo' => 111,
            'bar' => 222,
        ));
        $validObject = new ArrayIterator(array(
            'foo' => 111,
            'bar' => 222,
        ));
        $partiallyValidValuesArray = array(
            'foo' => 111,
            'bar' => 2.22,
        );
        $partiallyValidValuesObject = new ArrayIterator(array(
            'foo' => 111,
            'bar' => 2.22,
        ));
        $partiallyValidKeysArray = array(
            'foo' => 111,
            222 => 333,
        );
        $partiallyValidKeysObject = new ArrayIterator(array(
            'foo' => 111,
            222 => 333,
        ));

        $this->assertTrue($validator($validArray));
        $this->assertTrue($validator(array()));
        $this->assertTrue($validator($validObject));
        $this->assertTrue($validator(new ArrayIterator));

        $this->assertFalse($validator($partiallyValidValuesArray));
        $this->assertFalse($validator($partiallyValidKeysArray));
        $this->assertFalse($validator($partiallyValidValuesObject));
        $this->assertFalse($validator($partiallyValidKeysObject));
        $this->assertFalse($validator(null));
        $this->assertFalse($validator(true));
        $this->assertFalse($validator(false));
        $this->assertFalse($validator(111));
        $this->assertFalse($validator(1.11));
        $this->assertFalse($validator('foo'));
        $this->assertFalse($validator(new stdClass));
        $this->assertFalse($validator(stream_context_create()));
    }

    public function testVisitTupleTypeLogic()
    {
        $validator = $this->validatorFixture(new TupleType(array(
            new StringType,
            new IntegerType,
        )));
        $validTuple = array(
            'foo',
            111,
        );
        $partiallyValidTuple = array(
            'foo',
            'bar',
        );
        $invalidTupleNonVector = array(
            1 => 'foo',
            2 => 111,
        );

        $this->assertTrue($validator($validTuple));

        $this->assertFalse($validator($partiallyValidTuple));
        $this->assertFalse($validator($invalidTupleNonVector));
        $this->assertFalse($validator(null));
        $this->assertFalse($validator(true));
        $this->assertFalse($validator(false));
        $this->assertFalse($validator(111));
        $this->assertFalse($validator(1.11));
        $this->assertFalse($validator('foo'));
        $this->assertFalse($validator(new stdClass));
        $this->assertFalse($validator(stream_context_create()));
    }

    public function testVisitNullifiedType()
    {
        $type = new NullifiedType(new StringType);

        $this->assertNull($this->_generator->visitNullifiedType($type));
    }
}
