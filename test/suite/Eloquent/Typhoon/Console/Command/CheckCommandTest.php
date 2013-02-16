<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2013 Erin Millard
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
use Eloquent\Typhoon\CodeAnalysis\Issue\IssueRenderer;
use Eloquent\Typhoon\CodeAnalysis\Issue\IssueSeverity;
use Eloquent\Typhoon\Configuration\Configuration;
use Eloquent\Typhoon\TestCase\MultiGenerationTestCase;
use Icecave\Pasta\AST\Type\AccessModifier;
use Phake;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;

/**
 * @covers \Eloquent\Typhoon\Console\Command\CheckCommand
 * @covers \Eloquent\Typhoon\Console\Command\Command
 */
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
        $this->_issueRenderer = new IssueRenderer;
        $this->_isolator = Phake::mock('Icecave\Isolator\Isolator');
        $this->_command = Phake::partialMock(
            __NAMESPACE__.'\CheckCommand',
            $this->_analyzer,
            $this->_issueRenderer,
            $this->_isolator
        );
        Phake::when($this->_command)
            ->getApplication(Phake::anyParameters())
            ->thenReturn($this->_application)
        ;
        Phake::when($this->_command)
            ->includeLoaders(Phake::anyParameters())
            ->thenReturn(null)
        ;

        $this->_classDefinition = new ClassDefinition(ClassName::fromString('A'));
        $this->_methodDefinition = Phake::mock(
            'Eloquent\Typhoon\ClassMapper\MethodDefinition'
        );

        $this->_warning = Phake::partialMock(
            'Eloquent\Typhoon\CodeAnalysis\Issue\MethodRelated\MethodIssue',
            $this->_classDefinition,
            $this->_methodDefinition
        );
        Phake::when($this->_warning)
            ->severity(Phake::anyParameters())
            ->thenReturn(IssueSeverity::WARNING())
        ;
        $this->_error = Phake::partialMock(
            'Eloquent\Typhoon\CodeAnalysis\Issue\MethodRelated\MethodError',
            $this->_classDefinition,
            $this->_methodDefinition
        );
    }

    public function testConstructor()
    {
        $this->assertSame($this->_analyzer, $this->_command->analyzer());
        $this->assertSame($this->_issueRenderer, $this->_command->issueRenderer());
    }

    public function testConstructorDefaults()
    {
        $command = new CheckCommand;

        $this->assertInstanceOf(
            'Eloquent\Typhoon\CodeAnalysis\ProjectAnalyzer',
            $command->analyzer()
        );
        $this->assertInstanceOf(
            'Eloquent\Typhoon\CodeAnalysis\Issue\IssueRenderer',
            $command->issueRenderer()
        );
    }

    public function testConfigure()
    {
        $this->assertSame('check', $this->_command->getName());
        $this->assertSame(
            'Checks for correct Typhoon setup within a project.',
            $this->_command->getDescription()
        );

        $inputDefinition = new InputDefinition;
        $inputDefinition->addArgument(
            new InputArgument(
                'path',
                InputArgument::OPTIONAL,
                'The path to the root of the project.',
                '.'
            )
        );

        $this->assertEquals($inputDefinition, $this->_command->getDefinition());
    }

    public function testExecuteSuccess()
    {
        $result = Phake::partialMock('Eloquent\Typhoon\CodeAnalysis\AnalysisResult');
        Phake::when($this->_analyzer)
            ->analyze(Phake::anyParameters())
            ->thenReturn($result)
        ;
        $input = Phake::mock('Symfony\Component\Console\Input\InputInterface');
        $output = Phake::mock('Symfony\Component\Console\Output\OutputInterface');
        $exitCode = Liberator::liberate($this->_command)->execute($input, $output);

        $this->assertSame(0, $exitCode);
        Phake::inOrder(
            Phake::verify($this->_command)->includeLoaders(
                $this->identicalTo($this->_configuration),
                $this->identicalTo($output)
            ),
            Phake::verify($output)->writeln('<info>Checking for correct Typhoon setup...</info>'),
            Phake::verify($this->_analyzer)->analyze(
                $this->identicalTo($this->_configuration)
            ),
            Phake::verify($result, Phake::times(2))->issues(),
            Phake::verify($output)->writeln('<info>No problems detected.</info>'),
            Phake::verify($result)->isError()
        );
    }

    public function testExecuteSuccessWithExplicitPath()
    {
        $result = Phake::partialMock('Eloquent\Typhoon\CodeAnalysis\AnalysisResult');
        Phake::when($this->_analyzer)
            ->analyze(Phake::anyParameters())
            ->thenReturn($result)
        ;
        $input = Phake::mock('Symfony\Component\Console\Input\InputInterface');
        $output = Phake::mock('Symfony\Component\Console\Output\OutputInterface');

        Phake::when($input)
            ->getArgument('path')
            ->thenReturn('/path/to/project')
        ;

        $exitCode = Liberator::liberate($this->_command)->execute($input, $output);

        $this->assertSame(0, $exitCode);
        Phake::inOrder(
            Phake::verify($this->_isolator)->chdir('/path/to/project'),
            Phake::verify($this->_command)->includeLoaders(
                $this->identicalTo($this->_configuration),
                $this->identicalTo($output)
            ),
            Phake::verify($output)->writeln('<info>Checking for correct Typhoon setup...</info>'),
            Phake::verify($this->_analyzer)->analyze(
                $this->identicalTo($this->_configuration)
            ),
            Phake::verify($result, Phake::times(2))->issues(),
            Phake::verify($output)->writeln('<info>No problems detected.</info>'),
            Phake::verify($result)->isError()
        );
    }

    public function testExecuteFailure()
    {
        $result = Phake::partialMock(
            'Eloquent\Typhoon\CodeAnalysis\AnalysisResult',
            array(
                $this->_error,
                $this->_warning,
            )
        );
        Phake::when($this->_analyzer)
            ->analyze(Phake::anyParameters())
            ->thenReturn($result)
        ;
        Phake::when($this->_command)
            ->generateErrorBlock(Phake::anyParameters())
            ->thenReturn('foo')
        ;
        Phake::when($this->_command)
            ->generateWarningBlock(Phake::anyParameters())
            ->thenReturn('bar')
        ;
        $input = Phake::mock('Symfony\Component\Console\Input\InputInterface');
        $output = Phake::mock('Symfony\Component\Console\Output\OutputInterface');
        $exitCode = Liberator::liberate($this->_command)->execute($input, $output);

        $this->assertSame(1, $exitCode);
        Phake::inOrder(
            Phake::verify($this->_command)->includeLoaders(
                $this->identicalTo($this->_configuration),
                $this->identicalTo($output)
            ),
            Phake::verify($output)->writeln('<info>Checking for correct Typhoon setup...</info>'),
            Phake::verify($this->_analyzer)->analyze(
                $this->identicalTo($this->_configuration)
            ),
            Phake::verify($result, Phake::atLeast(1))->issues(),
            Phake::verify($result, Phake::atLeast(1))->issuesBySeverity(IssueSeverity::ERROR()),
            Phake::verify($output, Phake::times(2))->writeln(''),
            Phake::verify($output)->writeln('foo'),
            Phake::verify($result, Phake::atLeast(1))->issuesBySeverity(IssueSeverity::WARNING()),
            Phake::verify($output, Phake::times(2))->writeln(''),
            Phake::verify($output)->writeln('bar')
        );
    }

    public function testExecuteWarning()
    {
        $result = Phake::partialMock(
            'Eloquent\Typhoon\CodeAnalysis\AnalysisResult',
            array(
                $this->_warning,
            )
        );
        Phake::when($this->_analyzer)
            ->analyze(Phake::anyParameters())
            ->thenReturn($result)
        ;
        Phake::when($this->_command)
            ->generateWarningBlock(Phake::anyParameters())
            ->thenReturn('bar')
        ;
        $input = Phake::mock('Symfony\Component\Console\Input\InputInterface');
        $output = Phake::mock('Symfony\Component\Console\Output\OutputInterface');
        $exitCode = Liberator::liberate($this->_command)->execute($input, $output);

        $this->assertSame(0, $exitCode);
        Phake::inOrder(
            Phake::verify($this->_command)->includeLoaders(
                $this->identicalTo($this->_configuration),
                $this->identicalTo($output)
            ),
            Phake::verify($output)->writeln('<info>Checking for correct Typhoon setup...</info>'),
            Phake::verify($this->_analyzer)->analyze(
                $this->identicalTo($this->_configuration)
            ),
            Phake::verify($result, Phake::atLeast(1))->issues(),
            Phake::verify($result, Phake::atLeast(1))->issuesBySeverity(IssueSeverity::WARNING()),
            Phake::verify($output)->writeln(''),
            Phake::verify($output)->writeln('bar')
        );
    }

    public function testGenerateErrorBlock()
    {
        Phake::when($this->_command)
            ->generateBlock(Phake::anyParameters())
            ->thenReturn('foo')
        ;
        $result = Phake::partialMock(
            'Eloquent\Typhoon\CodeAnalysis\AnalysisResult',
            array(
                $this->_error,
                $this->_warning,
            )
        );
        $actual = Liberator::liberate($this->_command)->generateErrorBlock($result);

        $this->assertSame('foo', $actual);
        Phake::verify($this->_command)->generateBlock(
            'Problems detected',
            'error',
            $this->identicalTo(array('\A' => array($this->_error)))
        );
    }

    public function testGenerateWarningBlock()
    {
        Phake::when($this->_command)
            ->generateBlock(Phake::anyParameters())
            ->thenReturn('foo')
        ;
        $result = Phake::partialMock(
            'Eloquent\Typhoon\CodeAnalysis\AnalysisResult',
            array(
                $this->_error,
                $this->_warning,
            )
        );
        $actual = Liberator::liberate($this->_command)->generateWarningBlock($result);

        $this->assertSame('foo', $actual);
        Phake::verify($this->_command)->generateBlock(
            'Potential problems detected',
            'comment',
            $this->identicalTo(array('\A' => array($this->_warning)))
        );
    }

    public function testGenerateBlock()
    {
        $formatter = Phake::mock('Symfony\Component\Console\Helper\FormatterHelper');
        Phake::when($formatter)
            ->formatBlock(Phake::anyParameters())
            ->thenReturn('foo')
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
        $issueA = Phake::mock('Eloquent\Typhoon\CodeAnalysis\Issue\Issue');
        Phake::when($issueA)->accept(Phake::anyParameters())->thenReturn('bar');
        $issueB = Phake::mock('Eloquent\Typhoon\CodeAnalysis\Issue\Issue');
        Phake::when($issueB)->accept(Phake::anyParameters())->thenReturn('baz');
        $issues = array(
            '\qux' => array(
                $issueA,
                $issueB,
            ),
            '\doom' => array(
                $issueA,
                $issueB,
            ),
        );
        $expected = array(
          '[splat]',
          '',
          '  [\qux]',
          '    - bar',
          '    - baz',
          '',
          '  [\doom]',
          '    - bar',
          '    - baz',
        );

        $this->assertSame(
            'foo',
            Liberator::liberate($this->_command)->generateBlock('splat', 'ping', $issues)
        );
        Phake::verify($formatter)->formatBlock($expected, 'ping', true);
    }

    public function testIncludeLoaders()
    {
        $this->_command = Phake::partialMock(
            __NAMESPACE__.'\CheckCommand',
            $this->_analyzer,
            $this->_issueRenderer,
            $this->_isolator
        );
        $output = Phake::mock('Symfony\Component\Console\Output\OutputInterface');
        Liberator::liberate($this->_command)->includeLoaders($this->_configuration, $output);

        Phake::inOrder(
            Phake::verify($output)->writeln('<info>Including loaders...</info>'),
            Phake::verify($output)->writeln('  - foo'),
            Phake::verify($this->_isolator)->require('foo'),
            Phake::verify($output)->writeln('  - bar'),
            Phake::verify($this->_isolator)->require('bar')
        );
    }
}
