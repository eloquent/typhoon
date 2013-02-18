<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Generator\ParameterListMerge;

use Eloquent\Cosmos\ClassName;
use Eloquent\Equality\Comparator;
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
use Eloquent\Typhoon\ClassMapper\ClassDefinition;
use Eloquent\Typhoon\ClassMapper\MethodDefinition;
use Eloquent\Typhoon\CodeAnalysis\Issue\ParameterIssue\DefinedParameterVariableLength;
use Eloquent\Typhoon\CodeAnalysis\Issue\ParameterIssue\DocumentedParameterByReferenceMismatch;
use Eloquent\Typhoon\CodeAnalysis\Issue\ParameterIssue\DocumentedParameterNameMismatch;
use Eloquent\Typhoon\CodeAnalysis\Issue\ParameterIssue\DocumentedParameterTypeMismatch;
use Eloquent\Typhoon\CodeAnalysis\Issue\ParameterIssue\DocumentedParameterUndefined;
use Eloquent\Typhoon\CodeAnalysis\Issue\ParameterIssue\UndocumentedParameter;
use Eloquent\Typhoon\Configuration\RuntimeConfiguration;
use Eloquent\Typhoon\Generator\NullifiedType;
use Eloquent\Typhoon\Parameter\Parameter;
use Eloquent\Typhoon\Parameter\ParameterList;
use Eloquent\Typhoon\TestCase\MultiGenerationTestCase;
use Icecave\Pasta\AST\Type\AccessModifier;
use Phake;
use ReflectionClass;

class MergeToolTest extends MultiGenerationTestCase
{
    public function __construct($name = null, array $data = array(), $dataName = '')
    {
        $reflectionParameterClass = new ReflectionClass('ReflectionParameter');
        $this->_nativeCallableAvailable = $reflectionParameterClass->hasMethod('isCallable');

        $this->_className = ClassName::fromString('\foo');
        $this->_methodDefinition = new MethodDefinition(
            $this->_className,
            'bar',
            false,
            false,
            AccessModifier::PUBLIC_(),
            111,
            'baz'
        );
        $this->_classDefinition = new ClassDefinition(
            $this->_className,
            array(),
            array($this->_methodDefinition)
        );

        parent::__construct($name, $data, $dataName);
    }

    protected function setUp()
    {
        parent::setUp();

        $this->_mergeTool = new MergeTool;
        $this->_comparator = new Comparator;
    }

    public function testConstructor()
    {
        $this->assertTrue($this->_mergeTool->throwOnError());
        $this->assertSame(array(), $this->_mergeTool->issues());
        $this->assertSame(
            $this->_nativeCallableAvailable,
            $this->_mergeTool->nativeCallableAvailable()
        );
    }

    public function testUseNativeCallableManualOff()
    {
        $mergeTool = Phake::partialMock(__NAMESPACE__.'\MergeTool');
        $configuration = new RuntimeConfiguration(
            ClassName::fromString('\foo'),
            false
        );

        $this->assertFalse($mergeTool->useNativeCallable($configuration));
        Phake::verify($mergeTool, Phake::never())->nativeCallableAvailable();
    }

    public function testUseNativeCallableNotAvailable()
    {
        $mergeTool = Phake::partialMock(__NAMESPACE__.'\MergeTool');
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
        $mergeTool = Phake::partialMock(__NAMESPACE__.'\MergeTool');
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

        $documentedParameterList = new ParameterList;
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
        $expected = $nativeParameterList;
        $data['Fall back to native if undocumented'] = array(
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
                    new MixedType,
                    'Baz description.',
                    false,
                    true
                ),
            )
        );
        $data['Don\'t add variable length parameter if parameter count is wrong'] = array(
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
        $this->_mergeTool = new MergeTool(false);

        $this->assertEquals($expected, $this->_mergeTool->merge(
            new RuntimeConfiguration,
            $this->_classDefinition,
            $this->_methodDefinition,
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
                new Parameter(
                    'qux',
                    new MixedType,
                    'Qux description.',
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
        $expected = "Error in method \\foo::bar(): Documented parameter \$baz not defined.";
        $expectedIssues = array(
            new DocumentedParameterUndefined(
                $this->_classDefinition,
                $this->_methodDefinition,
                'baz'
            ),
            new DocumentedParameterUndefined(
                $this->_classDefinition,
                $this->_methodDefinition,
                'qux'
            ),
        );
        $data['Documented parameter not defined'] = array(
            $expected,
            $expectedIssues,
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
                new Parameter(
                    'qux',
                    new MixedType,
                    'Qux description.',
                    false,
                    false
                ),
            )
        );
        $expected = "Error in method \\foo::bar(): Parameter \$baz is not documented.";
        $expectedIssues = array(
            new UndocumentedParameter(
                $this->_classDefinition,
                $this->_methodDefinition,
                'baz'
            ),
            new UndocumentedParameter(
                $this->_classDefinition,
                $this->_methodDefinition,
                'qux'
            ),
        );
        $data['Native parameter not documented'] = array(
            $expected,
            $expectedIssues,
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
        $expected = "Error in method \\foo::bar(): Documented parameter name \$bar does not match defined parameter name \$baz.";
        $expectedIssues = array(
            new DocumentedParameterNameMismatch(
                $this->_classDefinition,
                $this->_methodDefinition,
                'baz',
                'bar'
            ),
        );
        $data['Parameter name mismatch'] = array(
            $expected,
            $expectedIssues,
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
        $expected = "Error in method \\foo::bar(): Parameter \$bar is defined as by-reference but documented as by-value.";
        $expectedIssues = array(
            new DocumentedParameterByReferenceMismatch(
                $this->_classDefinition,
                $this->_methodDefinition,
                'bar',
                true
            ),
        );
        $data['Parameter by-reference mismatch'] = array(
            $expected,
            $expectedIssues,
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
        $expected = "Error in method \\foo::bar(): Variable-length parameter \$baz should only be documented, not defined.";
        $expectedIssues = array(
            new DefinedParameterVariableLength(
                $this->_classDefinition,
                $this->_methodDefinition,
                'baz'
            ),
        );
        $data['Non documentation-only variable length parameter'] = array(
            $expected,
            $expectedIssues,
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
        $expected = "Error in method \\foo::bar(): Documented parameter \$qux not defined.";
        $expectedIssues = array(
            new DocumentedParameterUndefined(
                $this->_classDefinition,
                $this->_methodDefinition,
                'qux'
            ),
        );
        $data['Variable length parameters with undefined penultimate parameter'] = array(
            $expected,
            $expectedIssues,
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
        array $expectedIssues,
        ParameterList $documentedParameterList,
        ParameterList $nativeParameterList
    ) {
        $this->setExpectedException(
            __NAMESPACE__.'\Exception\ParameterListMergeException',
            $expected
        );
        $this->_mergeTool->merge(
            new RuntimeConfiguration,
            $this->_classDefinition,
            $this->_methodDefinition,
            $documentedParameterList,
            $nativeParameterList
        );
    }

    /**
     * @dataProvider mergeFailureData
     */
    public function testMergeFailureIssues(
        $expected,
        array $expectedIssues,
        ParameterList $documentedParameterList,
        ParameterList $nativeParameterList
    ) {
        $this->_mergeTool = new MergeTool(false, $issues);
        $this->_mergeTool->merge(
            new RuntimeConfiguration,
            $this->_classDefinition,
            $this->_methodDefinition,
            $documentedParameterList,
            $nativeParameterList
        );

        $this->assertEquals($expectedIssues, $issues);
        $this->assertTrue($this->_comparator->equals($expectedIssues, $issues));
        $this->assertEquals($expectedIssues, $this->_mergeTool->issues());
        $this->assertTrue($this->_comparator->equals($expectedIssues, $this->_mergeTool->issues()));
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

        $documentedType = new NullType;
        $nativeType = new ObjectType;
        $expected = $nativeType;
        $data['Fallback to native when types are incompatible'] = array(
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
        $this->_mergeTool = new MergeTool(false);
        $actual = Liberator::liberate($this->_mergeTool)->mergeType(
            new RuntimeConfiguration,
            $this->_classDefinition,
            $this->_methodDefinition,
            'baz',
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
            $this->_classDefinition,
            $this->_methodDefinition,
            'baz',
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
            $this->_classDefinition,
            $this->_methodDefinition,
            'baz',
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
        $expected = "Error in method \\foo::bar(): Documented type 'array<string, object>' is not correct for defined type 'mixed' of parameter \$baz.";
        $data['Error when native is mixed and documented is array'] = array(
            $expected,
            $documentedType,
            $nativeType,
        );

        $documentedType = new MixedType;
        $nativeType = new TraversableType(
            new ArrayType,
            new MixedType,
            new MixedType
        );
        $expected = "Error in method \\foo::bar(): Documented type 'mixed' is not correct for defined type 'array' of parameter \$baz.";
        $data['Error when native is array and documented is mixed'] = array(
            $expected,
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
        $expected = "Error in method \\foo::bar(): Documented type 'array|null' is not correct for defined type 'array' of parameter \$baz.";
        $data['Error when native is array and documented is array or null'] = array(
            $expected,
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
        $expected = "Error in method \\foo::bar(): Documented type 'array' is not correct for defined type 'array|null' of parameter \$baz.";
        $data['Error when native is array or null and documented is array'] = array(
            $expected,
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
        $expected = "Error in method \\foo::bar(): Documented type 'array|null' is not correct for defined type 'mixed' of parameter \$baz.";
        $data['Error when native is mixed and documented is array or null'] = array(
            $expected,
            $documentedType,
            $nativeType,
        );

        // objects
        $documentedType = new ObjectType(ClassName::fromString('Baz'));
        $nativeType = new MixedType;
        $expected = "Error in method \\foo::bar(): Documented type 'Baz' is not correct for defined type 'mixed' of parameter \$baz.";
        $data['Error when native is mixed and documented is object of type'] = array(
            $expected,
            $documentedType,
            $nativeType,
        );

        $documentedType = new MixedType;
        $nativeType = new ObjectType(ClassName::fromString('Baz'));
        $expected = "Error in method \\foo::bar(): Documented type 'mixed' is not correct for defined type 'Baz' of parameter \$baz.";
        $data['Error when native is object of type and documented is mixed'] = array(
            $expected,
            $documentedType,
            $nativeType,
        );

        $documentedType = new OrType(array(
            new ObjectType(ClassName::fromString('Baz')),
            new NullType,
        ));
        $nativeType = new ObjectType(ClassName::fromString('Baz'));
        $expected = "Error in method \\foo::bar(): Documented type 'Baz|null' is not correct for defined type 'Baz' of parameter \$baz.";
        $data['Error when native is object of type and documented is object of type or null'] = array(
            $expected,
            $documentedType,
            $nativeType,
        );

        $documentedType = new ObjectType(ClassName::fromString('Baz'));
        $nativeType = new OrType(array(
            new ObjectType(ClassName::fromString('Baz')),
            new NullType,
        ));
        $expected = "Error in method \\foo::bar(): Documented type 'Baz' is not correct for defined type 'Baz|null' of parameter \$baz.";
        $data['Error when native is object of type or null and documented is object of type'] = array(
            $expected,
            $documentedType,
            $nativeType,
        );

        $documentedType = new OrType(array(
            new ObjectType(ClassName::fromString('Baz')),
            new NullType,
        ));
        $nativeType = new MixedType;
        $expected = "Error in method \\foo::bar(): Documented type 'Baz|null' is not correct for defined type 'mixed' of parameter \$baz.";
        $data['Error when native is mixed and documented is object of type or null'] = array(
            $expected,
            $documentedType,
            $nativeType,
        );

        $documentedType = new ObjectType(ClassName::fromString('stdClass'));
        $nativeType = new ObjectType(ClassName::fromString('Iterator'));
        $expected = "Error in method \\foo::bar(): Documented type 'stdClass' is not correct for defined type 'Iterator' of parameter \$baz.";
        $data['Error when native is a class and documented is an incompatible class'] = array(
            $expected,
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
        $expected = "Error in method \\foo::bar(): Documented type 'Baz|float|null' is not correct for defined type 'Baz|null' of parameter \$baz.";
        $data['Error when documented and native are both or composites, and one of the documented types is incompatible'] = array(
            $expected,
            $documentedType,
            $nativeType,
        );

        // and composites
        $documentedType = new AndType(array(
            new ObjectType(ClassName::fromString('Traversable')),
            new ObjectType(ClassName::fromString('Serializable')),
        ));
        $nativeType = new ObjectType(ClassName::fromString('Iterator'));
        $expected = "Error in method \\foo::bar(): Documented type 'Traversable+Serializable' is not correct for defined type 'Iterator' of parameter \$baz.";
        $data['Error when native is not compatible with all types in AND composite'] = array(
            $expected,
            $documentedType,
            $nativeType,
        );

        // callables
        if ($this->_nativeCallableAvailable) {
            $documentedType = new CallableType;
            $nativeType = new MixedType;
            $expected = "Error in method \\foo::bar(): Documented type 'callable' is not correct for defined type 'mixed' of parameter \$baz.";
            $data['Error when native is mixed and documented is callable'] = array(
                $expected,
                $documentedType,
                $nativeType,
            );

            $documentedType = new MixedType;
            $nativeType = new CallableType;
            $expected = "Error in method \\foo::bar(): Documented type 'mixed' is not correct for defined type 'callable' of parameter \$baz.";
            $data['Error when native is callable and documented is mixed'] = array(
                $expected,
                $documentedType,
                $nativeType,
            );

            $documentedType = new OrType(array(
                new CallableType,
                new NullType,
            ));
            $nativeType = new CallableType;
            $expected = "Error in method \\foo::bar(): Documented type 'callable|null' is not correct for defined type 'callable' of parameter \$baz.";
            $data['Error when native is callable and documented is callable or null'] = array(
                $expected,
                $documentedType,
                $nativeType,
            );

            $documentedType = new CallableType;
            $nativeType = new OrType(array(
                new CallableType,
                new NullType,
            ));
            $expected = "Error in method \\foo::bar(): Documented type 'callable' is not correct for defined type 'callable|null' of parameter \$baz.";
            $data['Error when native is callable or null and documented is callable'] = array(
                $expected,
                $documentedType,
                $nativeType,
            );

            $documentedType = new OrType(array(
                new CallableType,
                new NullType,
            ));
            $nativeType = new MixedType;
            $expected = "Error in method \\foo::bar(): Documented type 'callable|null' is not correct for defined type 'mixed' of parameter \$baz.";
            $data['Error when native is mixed and documented is callable or null'] = array(
                $expected,
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
        Type $documentedType,
        Type $nativeType
    ) {
        $this->setExpectedException(
            __NAMESPACE__.'\Exception\ParameterListMergeException',
            $expected
        );
        Liberator::liberate($this->_mergeTool)->mergeType(
            new RuntimeConfiguration,
            $this->_classDefinition,
            $this->_methodDefinition,
            'baz',
            $documentedType,
            $nativeType
        );
    }

    /**
     * @dataProvider mergeTypeFailureData
     */
    public function testMergeTypeFailureIssues(
        $expected,
        Type $documentedType,
        Type $nativeType
    ) {
        $this->_mergeTool = new MergeTool(false, $issues);
        Liberator::liberate($this->_mergeTool)->mergeType(
            new RuntimeConfiguration,
            $this->_classDefinition,
            $this->_methodDefinition,
            'baz',
            $documentedType,
            $nativeType
        );
        $expectedIssues = array(
            new DocumentedParameterTypeMismatch(
                $this->_classDefinition,
                $this->_methodDefinition,
                'baz',
                $nativeType,
                $documentedType
            ),
        );

        $this->assertEquals($expectedIssues, $issues);
        $this->assertTrue($this->_comparator->equals($expectedIssues, $issues));
        $this->assertEquals($expectedIssues, $this->_mergeTool->issues());
        $this->assertTrue($this->_comparator->equals($expectedIssues, $this->_mergeTool->issues()));
    }
}
