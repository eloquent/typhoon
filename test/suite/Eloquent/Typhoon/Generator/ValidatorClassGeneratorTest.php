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
use FilesystemIterator;
use Phake;
use PHPUnit_Framework_TestCase;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class ValidatorClassGeneratorTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->_generator = new ValidatorClassGenerator;
    }

    public function testConstructor()
    {
        $parser = Phake::mock('Eloquent\Typhoon\Parser\ParameterListParser');
        $compiler = Phake::mock('Eloquent\Typhoon\Compiler\ParameterListCompiler');
        $generator = new ValidatorClassGenerator(
            $parser,
            $compiler
        );

        $this->assertSame($parser, $generator->parser());
        $this->assertSame($compiler, $generator->compiler());
    }

    public function testConstructorDefaults()
    {
        $this->assertInstanceOf(
            'Eloquent\Typhoon\Parser\ParameterListParser',
            $this->_generator->parser()
        );
        $this->assertInstanceOf(
            'Eloquent\Typhoon\Compiler\ParameterListCompiler',
            $this->_generator->compiler()
        );
    }

    public function generateData()
    {
        $exampleClassesPath =
            __DIR__.
            '/../../../../src/Eloquent/Typhoon/TestFixture/GeneratorExamples/'
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
            '/../../../../src/Eloquent/Typhoon/TestFixture/GeneratorExamples/'.
            $className.
            '.php'
        ;
        $expectedPath =
            __DIR__.
            '/../../../../src/Typhoon/Eloquent/Typhoon/TestFixture/GeneratorExamples/'.
            $className.
            'Typhoon.php'
        ;
        $classMapper = new ClassMapper;
        $classDefinitions = $classMapper->classesByFile($classPath);
        $classDefinition = array_pop($classDefinitions);
        $expected = file_get_contents($expectedPath);

        $this->assertSame($expected, $this->_generator->generate($classDefinition));
    }
}
