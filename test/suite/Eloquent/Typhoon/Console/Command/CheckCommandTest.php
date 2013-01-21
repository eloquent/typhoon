<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Console\Command;

use Eloquent\Cosmos\ClassName;
use Eloquent\Liberator\Liberator;
use Eloquent\Typhoon\ClassMapper\ClassDefinition;
use Eloquent\Typhoon\ClassMapper\MethodDefinition;
use Eloquent\Typhoon\CodeAnalysis\AnalysisResult;
use Eloquent\Typhoon\Configuration\Configuration;
use Eloquent\Typhoon\TestCase\MultiGenerationTestCase;
use Icecave\Pasta\AST\Type\AccessModifier;
use Phake;
use Symfony\Component\Console\Input\InputDefinition;

class CheckCommandTest extends MultiGenerationTestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->_configurationReader = Phake::mock('Eloquent\Typhoon\Configuration\ConfigurationReader');
        $this->_configuration = new Configuration(
            'baz',
            array(
                'qux',
                'doom',
            )
        );
        $this->_configuration->setLoaderPaths(array(
            'foo',
            'bar',
        ));
        Phake::when($this->_configurationReader)->read(Phake::anyParameters())->thenReturn($this->_configuration);
        $this->_application = Phake::mock('Eloquent\Typhoon\Console\Application');
        Phake::when($this->_application)->configurationReader()->thenReturn($this->_configurationReader);
        $this->_analyzer = Phake::mock('Eloquent\Typhoon\CodeAnalysis\ProjectAnalyzer');
        $this->_command = Phake::partialMock(
            __NAMESPACE__.'\CheckCommand',
            $this->_analyzer
        );
        Phake::when($this->_command)->getApplication()->thenReturn($this->_application);
    }

    public function testConstructor()
    {
        $this->assertSame($this->_analyzer, $this->_command->analyzer());
    }

    public function testConstructorDefaults()
    {
        $command = new CheckCommand;

        $this->assertInstanceOf(
            'Eloquent\Typhoon\CodeAnalysis\ProjectAnalyzer',
            $command->analyzer()
        );
    }

    public function testConfigure()
    {
        $this->assertSame('check', $this->_command->getName());
        $this->assertSame(
            'Checks for correct Typhoon setup within a project.',
            $this->_command->getDescription()
        );
        $this->assertEquals(new InputDefinition, $this->_command->getDefinition());
    }

    public function testExecuteSuccess()
    {
        $result = Phake::mock('Eloquent\Typhoon\CodeAnalysis\AnalysisResult');
        Phake::when($result)->isSuccessful(Phake::anyParameters())->thenReturn(true);
        Phake::when($this->_analyzer)
            ->analyze(Phake::anyParameters())
            ->thenReturn($result)
        ;
        $input = Phake::mock('Symfony\Component\Console\Input\InputInterface');
        $output = Phake::mock('Symfony\Component\Console\Output\OutputInterface');
        Liberator::liberate($this->_command)->execute($input, $output);

        Phake::inOrder(
            Phake::verify($output)->writeln('<info>Checking for correct Typhoon setup...</info>'),
            Phake::verify($this->_analyzer)->analyze(
                $this->identicalTo($this->_configuration)
            ),
            Phake::verify($result)->isSuccessful(),
            Phake::verify($output)->writeln('<info>No problems detected.</info>')
        );
    }

    public function testExecuteFailure()
    {
        $result = Phake::mock('Eloquent\Typhoon\CodeAnalysis\AnalysisResult');
        Phake::when($result)->isSuccessful(Phake::anyParameters())->thenReturn(false);
        Phake::when($this->_analyzer)
            ->analyze(Phake::anyParameters())
            ->thenReturn($result)
        ;
        Phake::when($this->_command)
            ->generateErrorBlock(Phake::anyParameters())
            ->thenReturn('foo')
        ;
        $input = Phake::mock('Symfony\Component\Console\Input\InputInterface');
        $output = Phake::mock('Symfony\Component\Console\Output\OutputInterface');
        Liberator::liberate($this->_command)->execute($input, $output);

        Phake::inOrder(
            Phake::verify($output)->writeln('<info>Checking for correct Typhoon setup...</info>'),
            Phake::verify($this->_analyzer)->analyze(
                $this->identicalTo($this->_configuration)
            ),
            Phake::verify($result)->isSuccessful(),
            Phake::verify($output)->writeln(''),
            Phake::verify($output)->writeln('foo')
        );
    }

    public function testGenerateErrorBlock()
    {
        $formatter = Phake::mock('Symfony\Component\Console\Helper\FormatterHelper');
        Phake::when($formatter)
            ->formatBlock(Phake::anyParameters())
            ->thenReturn('pang')
        ;
        $helperSet = Phake::mock('Symfony\Component\Console\Helper\HelperSet');
        Phake::when($helperSet)
            ->get(Phake::anyParameters())
            ->thenReturn($formatter)
        ;
        Phake::when($this->_command)
            ->getHelperSet(Phake::anyParameters())
            ->thenReturn($helperSet)
        ;
        $result = new AnalysisResult(array(
            new ClassDefinition(ClassName::fromString('\foo')),
            new ClassDefinition(ClassName::fromString('\bar')),
        ), array(
            new ClassDefinition(ClassName::fromString('\baz')),
            new ClassDefinition(ClassName::fromString('\qux')),
        ), array(
            array(
                new ClassDefinition(ClassName::fromString('\doom')),
                new MethodDefinition('splat', true, false, AccessModifier::PUBLIC_(), 111, ''),
            ),
            array(
                new ClassDefinition(ClassName::fromString('\ping')),
                new MethodDefinition('pong', true, false, AccessModifier::PUBLIC_(), 111, ''),
            ),
        ));
        $expected = array(
          '[Problems detected]',
          '',
          '  [foo]',
          '    - Incorrect or missing constructor initialization.',
          '',
          '  [bar]',
          '    - Incorrect or missing constructor initialization.',
          '',
          '  [baz]',
          '    - Incorrect or missing property definition.',
          '',
          '  [qux]',
          '    - Incorrect or missing property definition.',
          '',
          '  [doom]',
          '    - Incorrect or missing type check call in method splat().',
          '',
          '  [ping]',
          '    - Incorrect or missing type check call in method pong().',
        );

        $this->assertSame(
            'pang',
            Liberator::liberate($this->_command)->generateErrorBlock($result)
        );
        Phake::verify($formatter)->formatBlock($expected, 'error', true);
    }
}
