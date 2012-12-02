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
        $this->_nativeMergeTool = new NativeParameterListMergeTool;
        $this->_isolator = Phake::mock('IceCave\Isolator\Isolator');
        $this->_generator = Phake::partialMock(
            __NAMESPACE__.'\ValidatorClassGenerator',
            $this->_renderer,
            $this->_parser,
            $this->_parameterListGenerator,
            $this->_classMapper,
            $this->_nativeMergeTool,
            $this->_isolator
        );
    }

    public function testConstructor()
    {
        $this->assertSame($this->_renderer, $this->_generator->renderer());
        $this->assertSame($this->_parser, $this->_generator->parser());
        $this->assertSame($this->_parameterListGenerator, $this->_generator->generator());
        $this->assertSame($this->_classMapper, $this->_generator->classMapper());
        $this->assertSame($this->_nativeMergeTool, $this->_generator->nativeMergeTool());
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
            __NAMESPACE__.'\NativeParameterListMergeTool',
            $this->_generator->nativeMergeTool()
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
            '/../../../../src/Typhoon/Validator/Eloquent/Typhoon/TestFixture/SampleClasses/'.
            $className.
            'Typhoon.php'
        ;
        $configuration = new RuntimeConfiguration;
        $classDefinitions = $this->_classMapper->classesByFile($classPath);
        $classDefinition = array_pop($classDefinitions);
        $expected = file_get_contents($expectedPath);
        $actual = $this->_generator->generate($configuration, $classDefinition);

        $this->assertSame($expected, $actual);
    }

    public function testGenerateFromSource()
    {
        $configuration = new RuntimeConfiguration;
        $classDefinition = new ClassDefinition('Foo');
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
                Phake::setReference('baz'),
                Phake::setReference('qux')
            )
            ->thenReturn('doom')
        ;
        $actual = $this->_generator->generateFromSource(
            $configuration,
            'splat',
            'ping',
            $namespaceName,
            $className
        );

        $this->assertSame('doom', $actual);
        $this->assertSame('baz', $namespaceName);
        $this->assertSame('qux', $className);
        Phake::verify($this->_classMapper)->classBySource('splat', 'ping');
        Phake::verify($this->_generator)->generate(
            $this->identicalTo($configuration),
            $this->identicalTo($classDefinition),
            null,
            null
        );
    }

    public function testGenerateFromFile()
    {
        $configuration = new RuntimeConfiguration;
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
                'baz',
                'foo',
                Phake::setReference('qux'),
                Phake::setReference('doom')
            )
            ->thenReturn('splat')
        ;
        $actual = $this->_generator->generateFromFile(
            $configuration,
            'baz',
            'pip',
            $namespaceName,
            $className
        );

        $this->assertSame('splat', $actual);
        $this->assertSame('qux', $namespaceName);
        $this->assertSame('doom', $className);
        Phake::verify($this->_isolator)->file_get_contents('pip');
        Phake::verify($this->_generator)->generateFromSource(
            $this->identicalTo($configuration),
            'baz',
            'foo',
            null,
            null
        );
    }

    public function testGenerateFromClass()
    {
        $configuration = new RuntimeConfiguration;
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
                'foo',
                'bar',
                Phake::setReference('qux'),
                Phake::setReference('doom')
            )
            ->thenReturn('splat')
        ;
        $actual = $this->_generator->generateFromClass(
            $configuration,
            $class,
            $namespaceName,
            $className
        );

        $this->assertSame('splat', $actual);
        $this->assertSame('qux', $namespaceName);
        $this->assertSame('doom', $className);
        Phake::verify($class)->getName();
        Phake::verify($class)->getFileName();
        Phake::verify($this->_generator)->generateFromFile(
            $this->identicalTo($configuration),
            'foo',
            'bar',
            null,
            null
        );
    }
}
