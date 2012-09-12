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
use Eloquent\Typhoon\Parser\ParameterListParser;
use Eloquent\Typhoon\TestCase\MultiGenerationTestCase;
use Icecave\Pasta\AST\Expr\QualifiedIdentifier;
use Icecave\Pasta\AST\Identifier;
use Icecave\Pasta\AST\PhpBlock;
use Icecave\Pasta\AST\Stmt\NamespaceStatement;
use Icecave\Pasta\AST\SyntaxTree;
use Icecave\Pasta\AST\Type\ClassDefinition as ClassDefinitionASTNode;
use Icecave\Pasta\AST\Type\ConcreteMethod;
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
        $classDefinitions = $this->_classMapper->classesByFile($classPath);
        $classDefinition = array_pop($classDefinitions);
        $expected = file_get_contents($expectedPath);

        $this->assertSame($expected, $this->_generator->generate($classDefinition));
    }
}
