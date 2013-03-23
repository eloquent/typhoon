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

use Eloquent\Cosmos\ClassName;
use Eloquent\Typhoon\ClassMapper\ClassDefinition;
use Eloquent\Typhoon\ClassMapper\ClassMapper;
use Eloquent\Typhoon\Configuration\RuntimeConfiguration;
use Eloquent\Typhoon\Parser\ParameterListParser;
use Eloquent\Typhoon\TestCase\MultiGenerationTestCase;
use Icecave\Rasta\Renderer;
use Phake;

/**
 * @covers Eloquent\Typhoon\Generator\ValidatorClassGenerator
 * @covers Eloquent\Typhoon\Generator\ParameterListGenerator
 * @covers Eloquent\Typhoon\Generator\TyphaxASTGenerator
 */
class ValidatorClassGeneratorTest extends MultiGenerationTestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->_renderer = new Renderer;
        $this->_parser = new ParameterListParser;
        $this->_parameterListGenerator = new ParameterListGenerator;
        $this->_classMapper = Phake::partialMock(
            'Eloquent\Typhoon\ClassMapper\ClassMapper'
        );
        $this->_mergeTool = new ParameterListMerge\MergeTool;
        $this->_isolator = Phake::mock('IceCave\Isolator\Isolator');
        $this->_generator = Phake::partialMock(
            __NAMESPACE__.'\ValidatorClassGenerator',
            $this->_renderer,
            $this->_parser,
            $this->_parameterListGenerator,
            $this->_classMapper,
            $this->_mergeTool,
            $this->_isolator
        );
    }

    public function testConstructor()
    {
        $this->assertSame($this->_renderer, $this->_generator->renderer());
        $this->assertSame($this->_parser, $this->_generator->parser());
        $this->assertSame($this->_parameterListGenerator, $this->_generator->generator());
        $this->assertSame($this->_classMapper, $this->_generator->classMapper());
        $this->assertSame($this->_mergeTool, $this->_generator->mergeTool());
    }

    public function testConstructorDefaults()
    {
        $this->_generator = Phake::partialMock(
            __NAMESPACE__.'\ValidatorClassGenerator'
        );

        $this->assertInstanceOf(
            'Icecave\Rasta\Renderer',
            $this->_generator->renderer()
        );
        $this->assertInstanceOf(
            'Eloquent\Typhoon\Parser\ParameterListParser',
            $this->_generator->parser()
        );
        $this->assertInstanceOf(
            __NAMESPACE__.'\ParameterListGenerator',
            $this->_generator->generator()
        );
        $this->assertInstanceOf(
            'Eloquent\Typhoon\ClassMapper\ClassMapper',
            $this->_generator->classMapper()
        );
        $this->assertInstanceOf(
            __NAMESPACE__.'\ParameterListMerge\MergeTool',
            $this->_generator->mergeTool()
        );
    }

    public function generateData()
    {
        $exampleClassesPath =
            __DIR__.
            '/../../../../src/Eloquent/Typhoon/TestFixture/SampleClasses/'
        ;

        $data = array();
        foreach (scandir($exampleClassesPath) as $item) {
            if ('.' !== substr($item, 0, 1)) {
                $className = pathinfo($item, PATHINFO_FILENAME);
                $data[$className] = array($className);
            }
        }

        return $data;
    }

    /**
     * @dataProvider generateData
     */
    public function testGenerate($className)
    {
        $classPath =
            __DIR__.
            '/../../../../src/Eloquent/Typhoon/TestFixture/SampleClasses/'.
            $className.
            '.php'
        ;
        $expectedPath =
            __DIR__.
            '/../../../../src/foo/Validator/Eloquent/Typhoon/TestFixture/SampleClasses/'.
            $className.
            'TypeCheck.php'
        ;
        $configuration = new RuntimeConfiguration(ClassName::fromString('\foo'));
        $classDefinitions = $this->_classMapper->classesByFile($classPath);
        $classDefinition = array_pop($classDefinitions);
        $expected = file_get_contents($expectedPath);
        $actual = $this->_generator->generate($configuration, $classDefinition);

        $this->assertSame($expected, $actual);
    }

    public function testGenerateFromSource()
    {
        $configuration = new RuntimeConfiguration;
        $classDefinition = new ClassDefinition(
            ClassName::fromString('\Foo'),
            'class Foo {}'
        );
        $generatedClassName = ClassName::fromString('\qux');
        Phake::when($this->_classMapper)
            ->classBySource(Phake::anyParameters())
            ->thenReturn($classDefinition)
        ;
        Phake::when($this->_generator)
            ->generate(Phake::anyParameters())
            ->thenReturn('bar')
        ;
        Phake::when($this->_generator)
            ->generate(
                $this->identicalTo($configuration),
                $this->identicalTo($classDefinition),
                Phake::setReference($generatedClassName)
            )
            ->thenReturn('doom')
        ;
        $sourceClassName = ClassName::fromString('splat');
        $actual = $this->_generator->generateFromSource(
            $configuration,
            $sourceClassName,
            'ping',
            $className
        );

        $this->assertSame('doom', $actual);
        $this->assertSame($generatedClassName, $className);
        Phake::verify($this->_classMapper)->classBySource(
            $this->identicalTo($sourceClassName),
            'ping'
        );
        Phake::verify($this->_generator)->generate(
            $this->identicalTo($configuration),
            $this->identicalTo($classDefinition),
            null
        );
    }

    public function testGenerateFromFile()
    {
        $configuration = new RuntimeConfiguration;
        $sourceClassName = ClassName::fromString('baz');
        $generatedClassName = ClassName::fromString('\qux');
        Phake::when($this->_isolator)
            ->file_get_contents(Phake::anyParameters())
            ->thenReturn('foo')
        ;
        Phake::when($this->_generator)
            ->generateFromSource(Phake::anyParameters())
            ->thenReturn('bar')
        ;
        Phake::when($this->_generator)
            ->generateFromSource(
                $this->identicalTo($configuration),
                $this->identicalTo($sourceClassName),
                'foo',
                Phake::setReference($generatedClassName)
            )
            ->thenReturn('splat')
        ;
        $actual = $this->_generator->generateFromFile(
            $configuration,
            $sourceClassName,
            'pip',
            $className
        );

        $this->assertSame('splat', $actual);
        $this->assertSame($generatedClassName, $className);
        Phake::verify($this->_isolator)->file_get_contents('pip');
        Phake::verify($this->_generator)->generateFromSource(
            $this->identicalTo($configuration),
            $this->identicalTo($sourceClassName),
            'foo',
            null
        );
    }

    public function testGenerateFromClass()
    {
        $configuration = new RuntimeConfiguration;
        $generatedClassName = ClassName::fromString('\qux');
        $class = Phake::mock('ReflectionClass');
        Phake::when($class)->getName()->thenReturn('foo');
        Phake::when($class)->getFileName()->thenReturn('bar');
        Phake::when($this->_generator)
            ->generateFromFile(Phake::anyParameters())
            ->thenReturn('baz')
        ;
        Phake::when($this->_generator)
            ->generateFromFile(
                $this->identicalTo($configuration),
                ClassName::fromString('\foo'),
                'bar',
                Phake::setReference($generatedClassName)
            )
            ->thenReturn('splat')
        ;
        $actual = $this->_generator->generateFromClass(
            $configuration,
            $class,
            $className
        );

        $this->assertSame('splat', $actual);
        $this->assertSame($generatedClassName, $className);
        Phake::verify($class)->getName();
        Phake::verify($class)->getFileName();
        Phake::verify($this->_generator)->generateFromFile(
            $this->identicalTo($configuration),
            ClassName::fromString('\foo'),
            'bar',
            null
        );
    }
}
