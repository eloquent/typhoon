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
        $this->_classGenerator = Phake::mock(__NAMESPACE__.'\ValidatorClassGenerator');
        $this->_facadeGenerator = Phake::mock(__NAMESPACE__.'\FacadeGenerator');
        $this->_isolator = Phake::mock('Icecave\Isolator\Isolator');
        $this->_generator = Phake::partialMock(
            __NAMESPACE__.'\ProjectValidatorGenerator',
            $this->_classMapper,
            $this->_classGenerator,
            $this->_facadeGenerator,
            $this->_isolator
        );
    }

    public function testConstructor()
    {
        $this->assertSame($this->_classMapper, $this->_generator->classMapper());
        $this->assertSame($this->_classGenerator, $this->_generator->classGenerator());
        $this->assertSame($this->_facadeGenerator, $this->_generator->facadeGenerator());
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
            $generator->classGenerator()
        );
        $this->assertInstanceOf(
            __NAMESPACE__.'\FacadeGenerator',
            $generator->facadeGenerator()
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
        Phake::when($this->_classGenerator)
            ->generate(Phake::anyParameters())
            ->thenReturn(null)
        ;
        Phake::when($this->_classGenerator)
            ->generate(
                $this->identicalTo($configuration),
                $this->identicalTo($classDefinitionA),
                Phake::setReference('Namespace\Name\A'),
                Phake::setReference('Class_Name_A')
            )
            ->thenReturn('A source')
        ;
        Phake::when($this->_classGenerator)
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
        Phake::when($this->_isolator)
            ->is_dir(Phake::anyParameters())
            ->thenReturn(true)
            ->thenReturn(false)
        ;
        $this->_generator->generate($configuration);

        Phake::inOrder(
            Phake::verify($this->_generator)->buildClassMap(array('bar', 'baz')),
            Phake::verify($this->_classGenerator)->generate(
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
            Phake::verify($this->_classGenerator)->generate(
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
