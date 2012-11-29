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

use Eloquent\Liberator\Liberator;
use Eloquent\Typhoon\ClassMapper\ClassDefinition;
use Eloquent\Typhoon\Configuration\Configuration;
use Eloquent\Typhoon\TestCase\MultiGenerationTestCase;
use Phake;

class ProjectValidatorGeneratorTest extends MultiGenerationTestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->_classMapper = Phake::mock('Eloquent\Typhoon\ClassMapper\ClassMapper');
        $this->_validatorClassGenerator = Phake::mock(__NAMESPACE__.'\ValidatorClassGenerator');
        $this->_facadeGenerator = Phake::mock(__NAMESPACE__.'\FacadeGenerator');
        $this->_abstractValidatorGenerator = Phake::mock(__NAMESPACE__.'\AbstractValidatorGenerator');
        $this->_dummyValidatorGenerator = Phake::mock(__NAMESPACE__.'\DummyValidatorGenerator');
        $this->_typeInspectorGenerator = Phake::mock(__NAMESPACE__.'\TypeInspectorGenerator');
        $this->_unexpectedInputExceptionGenerator = Phake::mock(
            __NAMESPACE__.'\ExceptionGenerator\UnexpectedInputExceptionGenerator'
        );
        $this->_missingArgumentExceptionGenerator = Phake::mock(
            __NAMESPACE__.'\ExceptionGenerator\MissingArgumentExceptionGenerator'
        );
        $this->_unexpectedArgumentExceptionGenerator = Phake::mock(
            __NAMESPACE__.'\ExceptionGenerator\UnexpectedArgumentExceptionGenerator'
        );
        $this->_unexpectedArgumentValueExceptionGenerator = Phake::mock(
            __NAMESPACE__.'\ExceptionGenerator\UnexpectedArgumentValueExceptionGenerator'
        );
        $this->_isolator = Phake::mock('Icecave\Isolator\Isolator');
        $this->_generator = Phake::partialMock(
            __NAMESPACE__.'\ProjectValidatorGenerator',
            $this->_classMapper,
            $this->_validatorClassGenerator,
            $this->_facadeGenerator,
            $this->_abstractValidatorGenerator,
            $this->_dummyValidatorGenerator,
            $this->_typeInspectorGenerator,
            $this->_unexpectedInputExceptionGenerator,
            $this->_missingArgumentExceptionGenerator,
            $this->_unexpectedArgumentExceptionGenerator,
            $this->_unexpectedArgumentValueExceptionGenerator,
            $this->_isolator
        );
    }

    public function testConstructor()
    {
        $this->assertSame($this->_classMapper, $this->_generator->classMapper());
        $this->assertSame($this->_validatorClassGenerator, $this->_generator->validatorClassGenerator());
        $this->assertSame($this->_facadeGenerator, $this->_generator->facadeGenerator());
        $this->assertSame($this->_abstractValidatorGenerator, $this->_generator->abstractValidatorGenerator());
        $this->assertSame($this->_dummyValidatorGenerator, $this->_generator->dummyValidatorGenerator());
        $this->assertSame($this->_typeInspectorGenerator, $this->_generator->typeInspectorGenerator());
        $this->assertSame(
            $this->_unexpectedInputExceptionGenerator,
            $this->_generator->unexpectedInputExceptionGenerator()
        );
        $this->assertSame(
            $this->_missingArgumentExceptionGenerator,
            $this->_generator->missingArgumentExceptionGenerator()
        );
        $this->assertSame(
            $this->_unexpectedArgumentExceptionGenerator,
            $this->_generator->unexpectedArgumentExceptionGenerator()
        );
        $this->assertSame(
            $this->_unexpectedArgumentValueExceptionGenerator,
            $this->_generator->unexpectedArgumentValueExceptionGenerator()
        );
    }

    public function testConstructorDefaults()
    {
        $generator = new ProjectValidatorGenerator;

        $this->assertInstanceOf(
            'Eloquent\Typhoon\ClassMapper\ClassMapper',
            $generator->classMapper()
        );
        $this->assertInstanceOf(
            __NAMESPACE__.'\ValidatorClassGenerator',
            $generator->validatorClassGenerator()
        );
        $this->assertInstanceOf(
            __NAMESPACE__.'\FacadeGenerator',
            $generator->facadeGenerator()
        );
        $this->assertInstanceOf(
            __NAMESPACE__.'\AbstractValidatorGenerator',
            $generator->abstractValidatorGenerator()
        );
        $this->assertInstanceOf(
            __NAMESPACE__.'\DummyValidatorGenerator',
            $generator->dummyValidatorGenerator()
        );
        $this->assertInstanceOf(
            __NAMESPACE__.'\TypeInspectorGenerator',
            $generator->typeInspectorGenerator()
        );
        $this->assertInstanceOf(
            __NAMESPACE__.'\ExceptionGenerator\UnexpectedInputExceptionGenerator',
            $generator->unexpectedInputExceptionGenerator()
        );
        $this->assertInstanceOf(
            __NAMESPACE__.'\ExceptionGenerator\MissingArgumentExceptionGenerator',
            $generator->missingArgumentExceptionGenerator()
        );
        $this->assertInstanceOf(
            __NAMESPACE__.'\ExceptionGenerator\UnexpectedArgumentExceptionGenerator',
            $generator->unexpectedArgumentExceptionGenerator()
        );
        $this->assertInstanceOf(
            __NAMESPACE__.'\ExceptionGenerator\UnexpectedArgumentValueExceptionGenerator',
            $generator->unexpectedArgumentValueExceptionGenerator()
        );
    }

    public function testGenerate()
    {
        $configuration = new Configuration(
            'foo',
            array(
                'bar',
                'baz',
            )
        );
        $classDefinitionA = Phake::mock('Eloquent\Typhoon\ClassMapper\ClassDefinition');
        $classDefinitionB = Phake::mock('Eloquent\Typhoon\ClassMapper\ClassDefinition');
        $classMap = array(
            $classDefinitionA,
            $classDefinitionB,
        );
        Phake::when($this->_generator)
            ->buildClassMap(Phake::anyParameters())
            ->thenReturn($classMap)
        ;
        Phake::when($this->_validatorClassGenerator)
            ->generate(Phake::anyParameters())
            ->thenReturn(null)
        ;
        Phake::when($this->_validatorClassGenerator)
            ->generate(
                $this->identicalTo($configuration),
                $this->identicalTo($classDefinitionA),
                Phake::setReference('Namespace\Name\A'),
                Phake::setReference('Class_Name_A')
            )
            ->thenReturn('A source')
        ;
        Phake::when($this->_validatorClassGenerator)
            ->generate(
                $this->identicalTo($configuration),
                $this->identicalTo($classDefinitionB),
                Phake::setReference('Namespace\Name\B'),
                Phake::setReference('Class_Name_B')
            )
            ->thenReturn('B source')
        ;
        Phake::when($this->_facadeGenerator)
            ->generate(
                $this->identicalTo($configuration),
                Phake::setReference('Namespace\Name'),
                Phake::setReference('Facade_Class_Name')
            )
            ->thenReturn('Facade source')
        ;
        Phake::when($this->_abstractValidatorGenerator)
            ->generate(
                $this->identicalTo($configuration),
                Phake::setReference('Namespace\Name'),
                Phake::setReference('Abstract_Validator_Class_Name')
            )
            ->thenReturn('Abstract validator source')
        ;
        Phake::when($this->_dummyValidatorGenerator)
            ->generate(
                $this->identicalTo($configuration),
                Phake::setReference('Namespace\Name'),
                Phake::setReference('Dummy_Validator_Class_Name')
            )
            ->thenReturn('Dummy validator source')
        ;
        Phake::when($this->_typeInspectorGenerator)
            ->generate(
                $this->identicalTo($configuration),
                Phake::setReference('Namespace\Name'),
                Phake::setReference('Type_Inspector_Class_Name')
            )
            ->thenReturn('Type inspector source')
        ;
        Phake::when($this->_unexpectedInputExceptionGenerator)
            ->generate(
                $this->identicalTo($configuration),
                Phake::setReference('Typhoon\Exception'),
                Phake::setReference('UnexpectedInputException')
            )
            ->thenReturn('UnexpectedInputException source')
        ;
        Phake::when($this->_missingArgumentExceptionGenerator)
            ->generate(
                $this->identicalTo($configuration),
                Phake::setReference('Typhoon\Exception'),
                Phake::setReference('MissingArgumentException')
            )
            ->thenReturn('MissingArgumentException source')
        ;
        Phake::when($this->_unexpectedArgumentExceptionGenerator)
            ->generate(
                $this->identicalTo($configuration),
                Phake::setReference('Typhoon\Exception'),
                Phake::setReference('UnexpectedArgumentException')
            )
            ->thenReturn('UnexpectedArgumentException source')
        ;
        Phake::when($this->_unexpectedArgumentValueExceptionGenerator)
            ->generate(
                $this->identicalTo($configuration),
                Phake::setReference('Typhoon\Exception'),
                Phake::setReference('UnexpectedArgumentValueException')
            )
            ->thenReturn('UnexpectedArgumentValueException source')
        ;
        Phake::when($this->_isolator)
            ->is_dir(Phake::anyParameters())
            ->thenReturn(true)
            ->thenReturn(false)
            ->thenReturn(false)
            ->thenReturn(false)
            ->thenReturn(false)
            ->thenReturn(false)
            ->thenReturn(false)
            ->thenReturn(true)
            ->thenReturn(true)
            ->thenReturn(true)
        ;
        $this->_generator->generate($configuration);

        $exceptionIsDirVerification = Phake::verify($this->_isolator, Phake::times(4))
            ->is_dir('foo/Typhoon/Exception')
        ;
        Phake::inOrder(
            Phake::verify($this->_generator)->buildClassMap(array('bar', 'baz')),
            Phake::verify($this->_validatorClassGenerator)->generate(
                $this->identicalTo($configuration),
                $this->identicalTo($classDefinitionA),
                null,
                null
            ),
            Phake::verify($this->_isolator)->is_dir('foo/Namespace/Name/A/Class/Name'),
            Phake::verify($this->_isolator)->file_put_contents(
                'foo/Namespace/Name/A/Class/Name/A.php',
                'A source'
            ),
            Phake::verify($this->_validatorClassGenerator)->generate(
                $this->identicalTo($configuration),
                $this->identicalTo($classDefinitionB),
                null,
                null
            ),
            Phake::verify($this->_isolator)->is_dir('foo/Namespace/Name/B/Class/Name'),
            Phake::verify($this->_isolator)->mkdir(
                'foo/Namespace/Name/B/Class/Name',
                0777,
                true
            ),
            Phake::verify($this->_isolator)->file_put_contents(
                'foo/Namespace/Name/B/Class/Name/B.php',
                'B source'
            ),
            Phake::verify($this->_isolator)->is_dir('foo/Namespace/Name/Facade/Class'),
            Phake::verify($this->_isolator)->mkdir(
                'foo/Namespace/Name/Facade/Class',
                0777,
                true
            ),
            Phake::verify($this->_isolator)->file_put_contents(
                'foo/Namespace/Name/Facade/Class/Name.php',
                'Facade source'
            ),
            Phake::verify($this->_isolator)->is_dir('foo/Namespace/Name/Abstract/Validator/Class'),
            Phake::verify($this->_isolator)->mkdir(
                'foo/Namespace/Name/Abstract/Validator/Class',
                0777,
                true
            ),
            Phake::verify($this->_isolator)->file_put_contents(
                'foo/Namespace/Name/Abstract/Validator/Class/Name.php',
                'Abstract validator source'
            ),
            Phake::verify($this->_isolator)->is_dir('foo/Namespace/Name/Dummy/Validator/Class'),
            Phake::verify($this->_isolator)->mkdir(
                'foo/Namespace/Name/Dummy/Validator/Class',
                0777,
                true
            ),
            Phake::verify($this->_isolator)->file_put_contents(
                'foo/Namespace/Name/Dummy/Validator/Class/Name.php',
                'Dummy validator source'
            ),
            Phake::verify($this->_isolator)->is_dir('foo/Namespace/Name/Type/Inspector/Class'),
            Phake::verify($this->_isolator)->mkdir(
                'foo/Namespace/Name/Type/Inspector/Class',
                0777,
                true
            ),
            Phake::verify($this->_isolator)->file_put_contents(
                'foo/Namespace/Name/Type/Inspector/Class/Name.php',
                'Type inspector source'
            ),
            $exceptionIsDirVerification,
            Phake::verify($this->_isolator)->mkdir(
                'foo/Typhoon/Exception',
                0777,
                true
            ),
            Phake::verify($this->_isolator)->file_put_contents(
                'foo/Typhoon/Exception/UnexpectedInputException.php',
                'UnexpectedInputException source'
            ),
            $exceptionIsDirVerification,
            Phake::verify($this->_isolator)->file_put_contents(
                'foo/Typhoon/Exception/MissingArgumentException.php',
                'MissingArgumentException source'
            ),
            $exceptionIsDirVerification,
            Phake::verify($this->_isolator)->file_put_contents(
                'foo/Typhoon/Exception/UnexpectedArgumentException.php',
                'UnexpectedArgumentException source'
            ),
            $exceptionIsDirVerification,
            Phake::verify($this->_isolator)->file_put_contents(
                'foo/Typhoon/Exception/UnexpectedArgumentValueException.php',
                'UnexpectedArgumentValueException source'
            )
        );
        Phake::verify($this->_isolator, Phake::never())->mkdir(
            'foo/Namespace/Name/A/Class/Name',
            $this->anything(),
            $this->anything()
        );
    }

    public function testBuildClassMap()
    {
        $classDefinitionA = new ClassDefinition('A');
        $classDefinitionB = new ClassDefinition('B');
        $classDefinitionC = new ClassDefinition('C');
        $classDefinitionD = new ClassDefinition('D');
        Phake::when($this->_classMapper)
            ->classesByDirectory(Phake::anyParameters())
            ->thenReturn(array(
                $classDefinitionA,
                $classDefinitionB,
            ))
            ->thenReturn(array(
                $classDefinitionC,
                $classDefinitionD,
            ))
        ;
        $expected = array(
            $classDefinitionA,
            $classDefinitionB,
            $classDefinitionC,
            $classDefinitionD,
        );

        $this->assertSame($expected, Liberator::liberate($this->_generator)->buildClassMap(array('foo', 'bar')));
    }

    public function testPSRPath()
    {
        $namespaceName = 'Foo\Bar_Baz\Qux';
        $className = 'Doom_Splat_Pip';

        $this->assertSame(
            'Foo/Bar_Baz/Qux/Doom/Splat/Pip.php',
            Liberator::liberate($this->_generator)->PSRPath($namespaceName, $className)
        );
    }
}
