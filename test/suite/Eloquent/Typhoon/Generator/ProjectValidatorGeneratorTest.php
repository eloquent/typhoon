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
        $this->_staticClassGeneratorA = Phake::mock(__NAMESPACE__.'\StaticClassGenerator');
        $this->_staticClassGeneratorB = Phake::mock(__NAMESPACE__.'\StaticClassGenerator');
        $this->_staticClassGenerators = array(
            $this->_staticClassGeneratorA,
            $this->_staticClassGeneratorB
        );
        $this->_isolator = Phake::mock('Icecave\Isolator\Isolator');
        $this->_generator = Phake::partialMock(
            __NAMESPACE__.'\ProjectValidatorGenerator',
            $this->_classMapper,
            $this->_validatorClassGenerator,
            $this->_staticClassGenerators,
            $this->_isolator
        );
    }

    public function testConstructor()
    {
        $this->assertSame($this->_classMapper, $this->_generator->classMapper());
        $this->assertSame($this->_validatorClassGenerator, $this->_generator->validatorClassGenerator());
        $this->assertSame($this->_staticClassGenerators, $this->_generator->staticClassGenerators());
    }

    public function testConstructorDefaults()
    {
        $generator = new ProjectValidatorGenerator;
        $staticClassGenerators = $generator->staticClassGenerators();

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
            $staticClassGenerators[0]
        );
        $this->assertInstanceOf(
            __NAMESPACE__.'\AbstractValidatorGenerator',
            $staticClassGenerators[1]
        );
        $this->assertInstanceOf(
            __NAMESPACE__.'\DummyValidatorGenerator',
            $staticClassGenerators[2]
        );
        $this->assertInstanceOf(
            __NAMESPACE__.'\TypeInspectorGenerator',
            $staticClassGenerators[3]
        );
        $this->assertInstanceOf(
            __NAMESPACE__.'\ExceptionGenerator\UnexpectedInputExceptionGenerator',
            $staticClassGenerators[4]
        );
        $this->assertInstanceOf(
            __NAMESPACE__.'\ExceptionGenerator\MissingArgumentExceptionGenerator',
            $staticClassGenerators[5]
        );
        $this->assertInstanceOf(
            __NAMESPACE__.'\ExceptionGenerator\UnexpectedArgumentExceptionGenerator',
            $staticClassGenerators[6]
        );
        $this->assertInstanceOf(
            __NAMESPACE__.'\ExceptionGenerator\UnexpectedArgumentValueExceptionGenerator',
            $staticClassGenerators[7]
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
                Phake::setReference(ClassName::fromString('\Namespace\Name\A\Class_Name_A'))
            )
            ->thenReturn('A source')
        ;
        Phake::when($this->_validatorClassGenerator)
            ->generate(
                $this->identicalTo($configuration),
                $this->identicalTo($classDefinitionB),
                Phake::setReference(ClassName::fromString('\Namespace\Name\B\Class_Name_B'))
            )
            ->thenReturn('B source')
        ;
        Phake::when($this->_staticClassGeneratorA)
            ->generate(
                $this->identicalTo($configuration),
                Phake::setReference(ClassName::fromString('\Namespace\Name\Static_Class_A'))
            )
            ->thenReturn('Static class A source')
        ;
        Phake::when($this->_staticClassGeneratorB)
            ->generate(
                $this->identicalTo($configuration),
                Phake::setReference(ClassName::fromString('\Namespace\Name\Static_Class_B'))
            )
            ->thenReturn('Static class B source')
        ;
        Phake::when($this->_isolator)
            ->is_dir(Phake::anyParameters())
            ->thenReturn(true)
            ->thenReturn(false)
            ->thenReturn(false)
            ->thenReturn(true)
        ;
        $this->_generator->generate($configuration);

        Phake::inOrder(
            Phake::verify($this->_generator)->buildClassMap(array('bar', 'baz')),
            Phake::verify($this->_validatorClassGenerator)->generate(
                $this->identicalTo($configuration),
                $this->identicalTo($classDefinitionA),
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
            Phake::verify($this->_isolator, Phake::times(2))->is_dir('foo/Namespace/Name/Static/Class'),
            Phake::verify($this->_isolator)->mkdir(
                'foo/Namespace/Name/Static/Class',
                0777,
                true
            ),
            Phake::verify($this->_isolator)->file_put_contents(
                'foo/Namespace/Name/Static/Class/A.php',
                'Static class A source'
            ),
            Phake::verify($this->_isolator, Phake::times(2))->is_dir('foo/Namespace/Name/Static/Class'),
            Phake::verify($this->_isolator)->file_put_contents(
                'foo/Namespace/Name/Static/Class/B.php',
                'Static class B source'
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
        $classDefinitionA = new ClassDefinition(ClassName::fromString('A'));
        $classDefinitionB = new ClassDefinition(ClassName::fromString('B'));
        $classDefinitionC = new ClassDefinition(ClassName::fromString('C'));
        $classDefinitionD = new ClassDefinition(ClassName::fromString('D'));
        Phake::when($this->_classMapper)
            ->classesByPath(Phake::anyParameters())
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
        $className = ClassName::fromString('Foo\Bar_Baz\Qux\Doom_Splat_Pip');

        $this->assertSame(
            'Foo/Bar_Baz/Qux/Doom/Splat/Pip.php',
            Liberator::liberate($this->_generator)->PSRPath($className)
        );
    }
}
