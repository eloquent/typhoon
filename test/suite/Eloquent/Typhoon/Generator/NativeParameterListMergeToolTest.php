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
use Eloquent\Liberator\Liberator;
use Eloquent\Typhax\Type\AndType;
use Eloquent\Typhax\Type\BooleanType;
use Eloquent\Typhax\Type\ArrayType;
use Eloquent\Typhax\Type\CallableType;
use Eloquent\Typhax\Type\FloatType;
use Eloquent\Typhax\Type\MixedType;
use Eloquent\Typhax\Type\NullType;
use Eloquent\Typhax\Type\ObjectType;
use Eloquent\Typhax\Type\OrType;
use Eloquent\Typhax\Type\StringType;
use Eloquent\Typhax\Type\TraversableType;
use Eloquent\Typhax\Type\TupleType;
use Eloquent\Typhax\Type\Type;
use Eloquent\Typhoon\Configuration\RuntimeConfiguration;
use Eloquent\Typhoon\Parameter\Parameter;
use Eloquent\Typhoon\Parameter\ParameterList;
use Eloquent\Typhoon\TestCase\MultiGenerationTestCase;
use Phake;
use ReflectionClass;

class NativeParameterListMergeToolTest extends MultiGenerationTestCase
{
    public function __construct($name = null, array $data = array(), $dataName = '')
    {
        $reflectionParameterClass = new ReflectionClass('ReflectionParameter');
        $this->_nativeCallableAvailable = $reflectionParameterClass->hasMethod('isCallable');

        parent::__construct($name, $data, $dataName);
    }

    protected function setUp()
    {
        parent::setUp();

        $this->_mergeTool = new NativeParameterListMergeTool;
    }

    public function testNativeCallableAvailable()
    {
        $this->assertSame(
            $this->_nativeCallableAvailable,
            $this->_mergeTool->nativeCallableAvailable()
        );
    }

    public function testUseNativeCallableManualOff()
    {
        $mergeTool = Phake::partialMock(__NAMESPACE__.'\NativeParameterListMergeTool');
        $configuration = new RuntimeConfiguration(
            ClassName::fromString('\foo'),
            false
        );

        $this->assertFalse($mergeTool->useNativeCallable($configuration));
        Phake::verify($mergeTool, Phake::never())->nativeCallableAvailable();
    }

    public function testUseNativeCallableNotAvailable()
    {
        $mergeTool = Phake::partialMock(__NAMESPACE__.'\NativeParameterListMergeTool');
        Phake::when($mergeTool)->nativeCallableAvailable()->thenReturn(false);
        $configuration = new RuntimeConfiguration(
            ClassName::fromString('\foo'),
            true
        );

        $this->assertFalse($mergeTool->useNativeCallable($configuration));
        Phake::verify($mergeTool)->nativeCallableAvailable();
    }

    public function testUseNativeCallableOn()
    {
        $mergeTool = Phake::partialMock(__NAMESPACE__.'\NativeParameterListMergeTool');
        Phake::when($mergeTool)->nativeCallableAvailable()->thenReturn(true);
        $configuration = new RuntimeConfiguration(
            ClassName::fromString('\foo'),
            true
        );

        $this->assertTrue($mergeTool->useNativeCallable($configuration));
        Phake::verify($mergeTool)->nativeCallableAvailable();
    }

    public function mergeData()
    {
        $data = array();

        $documentedParameterList = new ParameterList;
        $nativeParameterList = new ParameterList;
        $expected = $documentedParameterList;
        $data['Empty parameter list'] = array(
            $expected,
            $documentedParameterList,
            $nativeParameterList,
        );

        $documentedParameterList = new ParameterList(
            array(
                new Parameter(
                    'bar',
                    new MixedType,
                    'Bar description.',
                    false,
                    false
                ),
                new Parameter(
                    'baz',
                    new MixedType,
                    'Baz description.',
                    false,
                    true
                ),
                new Parameter(
                    'qux',
                    new MixedType,
                    'Qux description.',
                    true,
                    true
                ),
            )
        );
        $nativeParameterList = new ParameterList(
            array(
                new Parameter(
                    'bar',
                    new MixedType,
                    'Bar description.',
                    false,
                    false
                ),
                new Parameter(
                    'baz',
                    new MixedType,
                    'Baz description.',
                    false,
                    true
                ),
                new Parameter(
                    'qux',
                    new MixedType,
                    'Qux description.',
                    true,
                    true
                ),
            )
        );
        $expected = new ParameterList(
            array(
                new Parameter(
                    'bar',
                    new NullifiedType(new MixedType),
                    'Bar description.',
                    false,
                    false
                ),
                new Parameter(
                    'baz',
                    new NullifiedType(new MixedType),
                    'Baz description.',
                    false,
                    true
                ),
                new Parameter(
                    'qux',
                    new NullifiedType(new MixedType),
                    'Qux description.',
                    true,
                    true
                ),
            )
        );
        $data['Unchanged parameter list'] = array(
            $expected,
            $documentedParameterList,
            $nativeParameterList,
        );

        $documentedParameterList = new ParameterList(
            array(
                new Parameter(
                    'bar',
                    new MixedType,
                    'Bar description.',
                    false,
                    false
                ),
                new Parameter(
                    'baz',
                    new MixedType,
                    'Baz description.',
                    false,
                    true
                ),
                new Parameter(
                    'qux',
                    new MixedType,
                    'Qux description.',
                    true,
                    true
                ),
            ),
            true
        );
        $nativeParameterList = new ParameterList(
            array(
                new Parameter(
                    'bar',
                    new MixedType,
                    'Bar description.',
                    false,
                    false
                ),
                new Parameter(
                    'baz',
                    new MixedType,
                    'Baz description.',
                    false,
                    true
                ),
            )
        );
        $expected = new ParameterList(
            array(
                new Parameter(
                    'bar',
                    new NullifiedType(new MixedType),
                    'Bar description.',
                    false,
                    false
                ),
                new Parameter(
                    'baz',
                    new NullifiedType(new MixedType),
                    'Baz description.',
                    false,
                    true
                ),
                new Parameter(
                    'qux',
                    new MixedType,
                    'Qux description.',
                    true,
                    true
                ),
            ),
            true
        );
        $data['Unchanged parameter list (variable length)'] = array(
            $expected,
            $documentedParameterList,
            $nativeParameterList,
        );

        $documentedParameterList = new ParameterList(
            array(
                new Parameter(
                    'bar',
                    new MixedType,
                    'Bar description.',
                    false,
                    false
                ),
            )
        );
        $nativeParameterList = new ParameterList(
            array(
                new Parameter(
                    'bar',
                    new MixedType,
                    null,
                    true,
                    false
                ),
            )
        );
        $expected = new ParameterList(
            array(
                new Parameter(
                    'bar',
                    new NullifiedType(new MixedType),
                    'Bar description.',
                    true,
                    false
                ),
            )
        );
        $data['Copy isOptional from native'] = array(
            $expected,
            $documentedParameterList,
            $nativeParameterList,
        );

        return $data;
    }

    /**
     * @dataProvider mergeData
     */
    public function testMerge(
        $expected,
        ParameterList $documentedParameterList,
        ParameterList $nativeParameterList
    ) {
        $this->assertEquals($expected, $this->_mergeTool->merge(
            new RuntimeConfiguration,
            'foo',
            $documentedParameterList,
            $nativeParameterList
        ));
    }

    public function mergeFailureData()
    {
        $data = array();

        $documentedParameterList = new ParameterList(
            array(
                new Parameter(
                    'bar',
                    new MixedType,
                    'Bar description.',
                    false,
                    false
                ),
                new Parameter(
                    'baz',
                    new MixedType,
                    'Baz description.',
                    false,
                    false
                ),
            )
        );
        $nativeParameterList = new ParameterList(
            array(
                new Parameter(
                    'bar',
                    new MixedType,
                    'Bar description.',
                    true,
                    false
                ),
            )
        );
        $expected = __NAMESPACE__.'\Exception\DocumentedParameterUndefinedException';
        $expectedMessage = "Documented parameter 'baz' not defined in 'foo'.";
        $data['Documented parameter not defined'] = array(
            $expected,
            $expectedMessage,
            $documentedParameterList,
            $nativeParameterList,
        );

        $documentedParameterList = new ParameterList(
            array(
                new Parameter(
                    'bar',
                    new MixedType,
                    'Bar description.',
                    false,
                    false
                ),
            )
        );
        $nativeParameterList = new ParameterList(
            array(
                new Parameter(
                    'bar',
                    new MixedType,
                    'Bar description.',
                    true,
                    false
                ),
                new Parameter(
                    'baz',
                    new MixedType,
                    'Baz description.',
                    false,
                    false
                ),
            )
        );
        $expected = __NAMESPACE__.'\Exception\UndocumentedParameterException';
        $expectedMessage = "Parameter 'baz' is undocumented in 'foo'.";
        $data['Native parameter not documented'] = array(
            $expected,
            $expectedMessage,
            $documentedParameterList,
            $nativeParameterList,
        );

        $documentedParameterList = new ParameterList(
            array(
                new Parameter(
                    'bar',
                    new MixedType,
                    'Bar description.',
                    false,
                    false
                ),
            )
        );
        $nativeParameterList = new ParameterList(
            array(
                new Parameter(
                    'baz',
                    new MixedType,
                    'Baz description.',
                    false,
                    false
                ),
            )
        );
        $expected = __NAMESPACE__.'\Exception\DocumentedParameterNameMismatchException';
        $expectedMessage = "Documented parameter name 'bar' does not match defined parameter name 'baz' in 'foo'.";
        $data['Parameter name mismatch'] = array(
            $expected,
            $expectedMessage,
            $documentedParameterList,
            $nativeParameterList,
        );

        $documentedParameterList = new ParameterList(
            array(
                new Parameter(
                    'bar',
                    new MixedType,
                    'Bar description.',
                    false,
                    false
                ),
            )
        );
        $nativeParameterList = new ParameterList(
            array(
                new Parameter(
                    'bar',
                    new MixedType,
                    'Bar description.',
                    false,
                    true
                ),
            )
        );
        $expected = __NAMESPACE__.'\Exception\DocumentedParameterByReferenceMismatchException';
        $expectedMessage = "Parameter 'bar' is documented as by-value but defined as by-reference in 'foo'.";
        $data['Parameter by-reference mismatch'] = array(
            $expected,
            $expectedMessage,
            $documentedParameterList,
            $nativeParameterList,
        );

        $documentedParameterList = new ParameterList(
            array(
                new Parameter(
                    'bar',
                    new MixedType,
                    'Bar description.',
                    false,
                    false
                ),
                new Parameter(
                    'baz',
                    new MixedType,
                    'Baz description.',
                    false,
                    true
                ),
            ),
            true
        );
        $nativeParameterList = new ParameterList(
            array(
                new Parameter(
                    'bar',
                    new MixedType,
                    'Bar description.',
                    false,
                    false
                ),
                new Parameter(
                    'baz',
                    new MixedType,
                    'Baz description.',
                    false,
                    true
                ),
            )
        );
        $expected = __NAMESPACE__.'\Exception\DefinedParameterVariableLengthException';
        $expectedMessage = "Variable-length parameter 'baz' should only be documented, not defined in 'foo'.";
        $data['Non documentation-only variable length parameter'] = array(
            $expected,
            $expectedMessage,
            $documentedParameterList,
            $nativeParameterList,
        );

        $documentedParameterList = new ParameterList(
            array(
                new Parameter(
                    'bar',
                    new MixedType,
                    'Bar description.',
                    false,
                    false
                ),
                new Parameter(
                    'baz',
                    new MixedType,
                    'Baz description.',
                    false,
                    true
                ),
                new Parameter(
                    'qux',
                    new MixedType,
                    'Qux description.',
                    true,
                    true
                ),
            ),
            true
        );
        $nativeParameterList = new ParameterList(
            array(
                new Parameter(
                    'bar',
                    new MixedType,
                    'Bar description.',
                    false,
                    false
                ),
            )
        );
        $expected = __NAMESPACE__.'\Exception\DocumentedParameterUndefinedException';
        $expectedMessage = "Documented parameter 'qux' not defined in 'foo'.";
        $data['Variable length parameters with undefined penultimate parameter'] = array(
            $expected,
            $expectedMessage,
            $documentedParameterList,
            $nativeParameterList,
        );

        return $data;
    }

    /**
     * @dataProvider mergeFailureData
     */
    public function testMergeFailure(
        $expected,
        $expectedMessage,
        ParameterList $documentedParameterList,
        ParameterList $nativeParameterList
    ) {
        try {
            $this->_mergeTool->merge(
                new RuntimeConfiguration,
                'foo',
                $documentedParameterList,
                $nativeParameterList
            );
        } catch (\Exception $e) {
            // I have no idea why this is necessary.
            // WHAT THE FUCK TRAVIS!?
            if ($e instanceof $expected) {
                $this->setExpectedException($expected, $expectedMessage);
            }

            throw $e;
        }

        $this->fail('Something went horribly, horribly wrong, and it is now time to panic.');
    }

    public function mergeTypeData()
    {
        $data = array();

        $documentedType = new TraversableType(
            new ArrayType,
            new MixedType,
            new MixedType
        );
        $nativeType = new TraversableType(
            new ArrayType,
            new MixedType,
            new MixedType
        );
        $expected = new NullifiedType($documentedType);
        $data['Both types documented as array'] = array(
            $expected,
            $documentedType,
            $nativeType,
        );

        $documentedType = new TraversableType(
            new ArrayType,
            new StringType,
            new ObjectType
        );
        $nativeType = new TraversableType(
            new ArrayType,
            new MixedType,
            new MixedType
        );
        $expected = $documentedType;
        $data['Both types documented as array, but documented specifies subtypes'] = array(
            $expected,
            $documentedType,
            $nativeType,
        );

        $documentedType = new OrType(array(
            new ObjectType(ClassName::fromString('Exception')),
            new NullType,
        ));
        $nativeType = $documentedType;
        $expected = new NullifiedType($documentedType);
        $data['Both types documented Exception|null'] = array(
            $expected,
            $documentedType,
            $nativeType,
        );

        $documentedType = new OrType(array(
            new FloatType,
            new NullType,
        ));
        $nativeType = new MixedType;
        $expected = $documentedType;
        $data['Documented type is not hintable and defined type is mixed'] = array(
            $expected,
            $documentedType,
            $nativeType,
        );

        $documentedType = new NullType;
        $nativeType = new MixedType;
        $expected = $documentedType;
        $data['Documented type is null, and native is mixed'] = array(
            $expected,
            $documentedType,
            $nativeType,
        );

        $documentedType = new ObjectType(ClassName::fromString('RecursiveDirectoryIterator'));
        $nativeType = new ObjectType(ClassName::fromString('FilesystemIterator'));
        $expected = $documentedType;
        $data['Native type is a parent class of documented class'] = array(
            $expected,
            $documentedType,
            $nativeType,
        );

        $documentedType = new BooleanType;
        $nativeType = new MixedType;
        $expected = $documentedType;
        $data["Don't clobber basic types"] = array(
            $expected,
            $documentedType,
            $nativeType,
        );

        $documentedType = new AndType(array(
            new ObjectType(ClassName::fromString('Iterator')),
            new ObjectType(ClassName::fromString('Countable')),
        ));
        $nativeType = new ObjectType(ClassName::fromString('Traversable'));
        $expected = $documentedType;
        $data['Native type is compatible with all types in AND composite'] = array(
            $expected,
            $documentedType,
            $nativeType,
        );

        $documentedType = new TupleType(array(
            new StringType,
            new FloatType,
        ));
        $nativeType = new TraversableType(
            new ArrayType,
            new MixedType,
            new MixedType
        );
        $expected = $documentedType;
        $data['Documented type is tuple, and native is array'] = array(
            $expected,
            $documentedType,
            $nativeType,
        );

        return $data;
    }

    /**
     * @dataProvider mergeTypeData
     */
    public function testMergeType(
        Type $expected,
        Type $documentedType,
        Type $nativeType
    ) {
        $actual = Liberator::liberate($this->_mergeTool)->mergeType(
            new RuntimeConfiguration,
            'foo',
            'bar',
            $documentedType,
            $nativeType
        );

        $this->assertEquals($expected, $actual);
    }

    public function testMergeTypeCallableMixed()
    {
        $documentedType = new CallableType;
        $nativeType = new MixedType;
        $configuration = new RuntimeConfiguration(
            ClassName::fromString('\foo'),
            false
        );
        $actual = Liberator::liberate($this->_mergeTool)->mergeType(
            $configuration,
            'foo',
            'bar',
            $documentedType,
            $nativeType
        );

        $this->assertEquals($documentedType, $actual);
    }

    public function testMergeTypeCallableOrNullMixed()
    {
        $documentedType = new OrType(array(
            new CallableType,
            new NullType,
        ));
        $nativeType = new MixedType;
        $configuration = new RuntimeConfiguration(
            ClassName::fromString('\foo'),
            false
        );
        $actual = Liberator::liberate($this->_mergeTool)->mergeType(
            $configuration,
            'foo',
            'bar',
            $documentedType,
            $nativeType
        );

        $this->assertEquals($documentedType, $actual);
    }

    public function mergeTypeFailureData()
    {
        $data = array();

        // arrays
        $documentedType = new TraversableType(
            new ArrayType,
            new StringType,
            new ObjectType
        );
        $nativeType = new MixedType;
        $expected = __NAMESPACE__.'\Exception\DocumentedParameterTypeMismatchException';
        $expectedMessage = "Documented type 'array<string, object>' is not correct for defined type 'mixed' for parameter 'bar' in 'foo'.";
        $data['Error when native is mixed and documented is array'] = array(
            $expected,
            $expectedMessage,
            $documentedType,
            $nativeType,
        );

        $documentedType = new MixedType;
        $nativeType = new TraversableType(
            new ArrayType,
            new MixedType,
            new MixedType
        );
        $expected = __NAMESPACE__.'\Exception\DocumentedParameterTypeMismatchException';
        $expectedMessage = "Documented type 'mixed' is not correct for defined type 'array' for parameter 'bar' in 'foo'.";
        $data['Error when native is array and documented is mixed'] = array(
            $expected,
            $expectedMessage,
            $documentedType,
            $nativeType,
        );

        $documentedType = new OrType(array(
            new TraversableType(
                new ArrayType,
                new MixedType,
                new MixedType
            ),
            new NullType,
        ));
        $nativeType = new TraversableType(
            new ArrayType,
            new MixedType,
            new MixedType
        );
        $expected = __NAMESPACE__.'\Exception\DocumentedParameterTypeMismatchException';
        $expectedMessage = "Documented type 'array|null' is not correct for defined type 'array' for parameter 'bar' in 'foo'.";
        $data['Error when native is array and documented is array or null'] = array(
            $expected,
            $expectedMessage,
            $documentedType,
            $nativeType,
        );

        $documentedType = new TraversableType(
            new ArrayType,
            new MixedType,
            new MixedType
        );
        $nativeType = new OrType(array(
            new TraversableType(
                new ArrayType,
                new MixedType,
                new MixedType
            ),
            new NullType,
        ));
        $expected = __NAMESPACE__.'\Exception\DocumentedParameterTypeMismatchException';
        $expectedMessage = "Documented type 'array' is not correct for defined type 'array|null' for parameter 'bar' in 'foo'.";
        $data['Error when native is array or null and documented is array'] = array(
            $expected,
            $expectedMessage,
            $documentedType,
            $nativeType,
        );

        $documentedType = new OrType(array(
            new TraversableType(
                new ArrayType,
                new MixedType,
                new MixedType
            ),
            new NullType,
        ));
        $nativeType = new MixedType;
        $expected = __NAMESPACE__.'\Exception\DocumentedParameterTypeMismatchException';
        $expectedMessage = "Documented type 'array|null' is not correct for defined type 'mixed' for parameter 'bar' in 'foo'.";
        $data['Error when native is mixed and documented is array or null'] = array(
            $expected,
            $expectedMessage,
            $documentedType,
            $nativeType,
        );

        // objects
        $documentedType = new ObjectType(ClassName::fromString('Baz'));
        $nativeType = new MixedType;
        $expected = __NAMESPACE__.'\Exception\DocumentedParameterTypeMismatchException';
        $expectedMessage = "Documented type 'Baz' is not correct for defined type 'mixed' for parameter 'bar' in 'foo'.";
        $data['Error when native is mixed and documented is object of type'] = array(
            $expected,
            $expectedMessage,
            $documentedType,
            $nativeType,
        );

        $documentedType = new MixedType;
        $nativeType = new ObjectType(ClassName::fromString('Baz'));
        $expected = __NAMESPACE__.'\Exception\DocumentedParameterTypeMismatchException';
        $expectedMessage = "Documented type 'mixed' is not correct for defined type 'Baz' for parameter 'bar' in 'foo'.";
        $data['Error when native is object of type and documented is mixed'] = array(
            $expected,
            $expectedMessage,
            $documentedType,
            $nativeType,
        );

        $documentedType = new OrType(array(
            new ObjectType(ClassName::fromString('Baz')),
            new NullType,
        ));
        $nativeType = new ObjectType(ClassName::fromString('Baz'));
        $expected = __NAMESPACE__.'\Exception\DocumentedParameterTypeMismatchException';
        $expectedMessage = "Documented type 'Baz|null' is not correct for defined type 'Baz' for parameter 'bar' in 'foo'.";
        $data['Error when native is object of type and documented is object of type or null'] = array(
            $expected,
            $expectedMessage,
            $documentedType,
            $nativeType,
        );

        $documentedType = new ObjectType(ClassName::fromString('Baz'));
        $nativeType = new OrType(array(
            new ObjectType(ClassName::fromString('Baz')),
            new NullType,
        ));
        $expected = __NAMESPACE__.'\Exception\DocumentedParameterTypeMismatchException';
        $expectedMessage = "Documented type 'Baz' is not correct for defined type 'Baz|null' for parameter 'bar' in 'foo'.";
        $data['Error when native is object of type or null and documented is object of type'] = array(
            $expected,
            $expectedMessage,
            $documentedType,
            $nativeType,
        );

        $documentedType = new OrType(array(
            new ObjectType(ClassName::fromString('Baz')),
            new NullType,
        ));
        $nativeType = new MixedType;
        $expected = __NAMESPACE__.'\Exception\DocumentedParameterTypeMismatchException';
        $expectedMessage = "Documented type 'Baz|null' is not correct for defined type 'mixed' for parameter 'bar' in 'foo'.";
        $data['Error when native is mixed and documented is object of type or null'] = array(
            $expected,
            $expectedMessage,
            $documentedType,
            $nativeType,
        );

        $documentedType = new ObjectType(ClassName::fromString('stdClass'));
        $nativeType = new ObjectType(ClassName::fromString('Iterator'));
        $expected = __NAMESPACE__.'\Exception\DocumentedParameterTypeMismatchException';
        $expectedMessage = "Documented type 'stdClass' is not correct for defined type 'Iterator' for parameter 'bar' in 'foo'.";
        $data['Error when native is a class and documented is an incompatible class'] = array(
            $expected,
            $expectedMessage,
            $documentedType,
            $nativeType,
        );

        // or composites
        $documentedType = new OrType(array(
            new ObjectType(ClassName::fromString('Baz')),
            new FloatType,
            new NullType,
        ));
        $nativeType = new OrType(array(
            new ObjectType(ClassName::fromString('Baz')),
            new NullType,
        ));
        $expected = __NAMESPACE__.'\Exception\DocumentedParameterTypeMismatchException';
        $expectedMessage = "Documented type 'Baz|float|null' is not correct for defined type 'Baz|null' for parameter 'bar' in 'foo'.";
        $data['Error when documented and native are both or composites, and one of the documented types is incompatible'] = array(
            $expected,
            $expectedMessage,
            $documentedType,
            $nativeType,
        );

        // and composites
        $documentedType = new AndType(array(
            new ObjectType(ClassName::fromString('Traversable')),
            new ObjectType(ClassName::fromString('Serializable')),
        ));
        $nativeType = new ObjectType(ClassName::fromString('Iterator'));
        $expected = __NAMESPACE__.'\Exception\DocumentedParameterTypeMismatchException';
        $expectedMessage = "Documented type 'Traversable+Serializable' is not correct for defined type 'Iterator' for parameter 'bar' in 'foo'.";
        $data['Error when native is not compatible with all types in AND composite'] = array(
            $expected,
            $expectedMessage,
            $documentedType,
            $nativeType,
        );

        // callables
        if ($this->_nativeCallableAvailable) {
            $documentedType = new CallableType;
            $nativeType = new MixedType;
            $expected = __NAMESPACE__.'\Exception\DocumentedParameterTypeMismatchException';
            $expectedMessage = "Documented type 'callable' is not correct for defined type 'mixed' for parameter 'bar' in 'foo'.";
            $data['Error when native is mixed and documented is callable'] = array(
                $expected,
                $expectedMessage,
                $documentedType,
                $nativeType,
            );

            $documentedType = new MixedType;
            $nativeType = new CallableType;
            $expected = __NAMESPACE__.'\Exception\DocumentedParameterTypeMismatchException';
            $expectedMessage = "Documented type 'mixed' is not correct for defined type 'callable' for parameter 'bar' in 'foo'.";
            $data['Error when native is callable and documented is mixed'] = array(
                $expected,
                $expectedMessage,
                $documentedType,
                $nativeType,
            );

            $documentedType = new OrType(array(
                new CallableType,
                new NullType,
            ));
            $nativeType = new CallableType;
            $expected = __NAMESPACE__.'\Exception\DocumentedParameterTypeMismatchException';
            $expectedMessage = "Documented type 'callable|null' is not correct for defined type 'callable' for parameter 'bar' in 'foo'.";
            $data['Error when native is callable and documented is callable or null'] = array(
                $expected,
                $expectedMessage,
                $documentedType,
                $nativeType,
            );

            $documentedType = new CallableType;
            $nativeType = new OrType(array(
                new CallableType,
                new NullType,
            ));
            $expected = __NAMESPACE__.'\Exception\DocumentedParameterTypeMismatchException';
            $expectedMessage = "Documented type 'callable' is not correct for defined type 'callable|null' for parameter 'bar' in 'foo'.";
            $data['Error when native is callable or null and documented is callable'] = array(
                $expected,
                $expectedMessage,
                $documentedType,
                $nativeType,
            );

            $documentedType = new OrType(array(
                new CallableType,
                new NullType,
            ));
            $nativeType = new MixedType;
            $expected = __NAMESPACE__.'\Exception\DocumentedParameterTypeMismatchException';
            $expectedMessage = "Documented type 'callable|null' is not correct for defined type 'mixed' for parameter 'bar' in 'foo'.";
            $data['Error when native is mixed and documented is callable or null'] = array(
                $expected,
                $expectedMessage,
                $documentedType,
                $nativeType,
            );
        }

        return $data;
    }

    /**
     * @dataProvider mergeTypeFailureData
     */
    public function testMergeTypeFailure(
        $expected,
        $expectedMessage,
        Type $documentedType,
        Type $nativeType
    ) {
        $this->setExpectedException($expected, $expectedMessage);
        Liberator::liberate($this->_mergeTool)->mergeType(
            new RuntimeConfiguration,
            'foo',
            'bar',
            $documentedType,
            $nativeType
        );
    }
}
