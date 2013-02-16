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

use Eloquent\Liberator\Liberator;
use Eloquent\Typhoon\Configuration\Configuration;
use Eloquent\Typhoon\TestCase\MultiGenerationTestCase;
use Phake;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;

/**
 * @covers \Eloquent\Typhoon\Console\Command\Command
 * @covers \Eloquent\Typhoon\Console\Command\GenerateCommand
 */
class GenerateCommandTest extends MultiGenerationTestCase
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
        $this->_generator = Phake::mock('Eloquent\Typhoon\Generator\ProjectValidatorGenerator');
        $this->_classGenerator = Phake::mock('Eloquent\Typhoon\Generator\ValidatorClassGenerator');
        Phake::when($this->_generator)->classGenerator()->thenReturn($this->_classGenerator);
        $this->_isolator = Phake::mock('Icecave\Isolator\Isolator');
        $this->_command = Phake::partialMock(
            __NAMESPACE__.'\GenerateCommand',
            $this->_generator,
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
    }

    public function testConstructor()
    {
        $this->assertSame($this->_generator, $this->_command->generator());
    }

    public function testConstructorDefaults()
    {
        $command = new GenerateCommand;

        $this->assertInstanceOf(
            'Eloquent\Typhoon\Generator\ProjectValidatorGenerator',
            $command->generator()
        );
    }

    public function testConfigure()
    {
        $this->assertSame('generate', $this->_command->getName());
        $this->assertSame(
            'Generates Typhoon classes for a project.',
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

    public function testExecute()
    {
        $input = Phake::mock('Symfony\Component\Console\Input\InputInterface');
        $output = Phake::mock('Symfony\Component\Console\Output\OutputInterface');
        Liberator::liberate($this->_command)->execute($input, $output);

        Phake::inOrder(
            Phake::verify($this->_command)->includeLoaders(
                $this->identicalTo($this->_configuration),
                $this->identicalTo($output)
            ),
            Phake::verify($output)->writeln('<info>Generating classes...</info>'),
            Phake::verify($this->_generator)->generate(
                $this->identicalTo($this->_configuration)
            ),
            Phake::verify($output)->writeln('<info>Done.</info>')
        );
    }

    public function testExecuteWithExplicitPath()
    {
        $input = Phake::mock('Symfony\Component\Console\Input\InputInterface');
        $output = Phake::mock('Symfony\Component\Console\Output\OutputInterface');

        Phake::when($input)
            ->getArgument('path')
            ->thenReturn('/path/to/project')
        ;

        Liberator::liberate($this->_command)->execute($input, $output);

        Phake::inOrder(
            Phake::verify($this->_isolator)->chdir('/path/to/project'),
            Phake::verify($this->_command)->includeLoaders(
                $this->identicalTo($this->_configuration),
                $this->identicalTo($output)
            ),
            Phake::verify($output)->writeln('<info>Generating classes...</info>'),
            Phake::verify($this->_generator)->generate(
                $this->identicalTo($this->_configuration)
            ),
            Phake::verify($output)->writeln('<info>Done.</info>')
        );
    }

    public function testIncludeLoaders()
    {
        $this->_command = Phake::partialMock(
            __NAMESPACE__.'\GenerateCommand',
            $this->_generator,
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
