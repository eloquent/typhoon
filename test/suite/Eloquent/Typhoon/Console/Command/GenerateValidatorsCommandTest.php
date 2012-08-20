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

use Eloquent\Liberator\Liberator;
use Phake;
use PHPUnit_Framework_TestCase;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;

class GenerateValidatorsCommandTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->_generator = Phake::mock('Eloquent\Typhoon\Generator\ProjectValidatorGenerator');
        $this->_deploymentManager = Phake::mock('Eloquent\Typhoon\Deployment\DeploymentManager');
        $this->_isolator = Phake::mock('Icecave\Isolator\Isolator');
        $this->_command = new GenerateValidatorsCommand(
            $this->_generator,
            $this->_deploymentManager,
            $this->_isolator
        );
    }

    public function testConstructor()
    {
        $this->assertSame($this->_generator, $this->_command->generator());
        $this->assertSame($this->_deploymentManager, $this->_command->deploymentManager());
    }

    public function testConstructorDefaults()
    {
        $command = new GenerateValidatorsCommand;

        $this->assertInstanceOf(
            'Eloquent\Typhoon\Generator\ProjectValidatorGenerator',
            $command->generator()
        );
        $this->assertInstanceOf(
            'Eloquent\Typhoon\Deployment\DeploymentManager',
            $command->deploymentManager()
        );
    }

    public function testConfigure()
    {
        $expected = new InputDefinition;
        $expected->addArgument(new InputArgument(
            'output-path',
            InputArgument::REQUIRED,
            'The path in which to create the validator classes.'
        ));
        $expected->addArgument(new InputArgument(
            'class-path',
            InputArgument::REQUIRED | InputArgument::IS_ARRAY,
            'One or more paths containing the source classes.'
        ));
        $expected->addOption(new InputOption(
            'loader-path',
            'l',
            InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
            'Path to one or more scripts used to load the source classes in the given path.',
            array(
                './vendor/autoload.php',
            )
        ));

        $this->assertEquals($expected, $this->_command->getDefinition());
    }

    public function testExecute()
    {
        $input = Phake::mock('Symfony\Component\Console\Input\InputInterface');
        Phake::when($input)->getOption('loader-path')->thenReturn(array(
            'foo',
            'bar',
        ));
        Phake::when($input)->getArgument('output-path')->thenReturn('baz');
        Phake::when($input)->getArgument('class-path')->thenReturn(array(
            'qux',
            'doom',
        ));
        $output = Phake::mock('Symfony\Component\Console\Output\OutputInterface');
        Liberator::liberate($this->_command)->execute($input, $output);

        Phake::inOrder(
            Phake::verify($output)->writeln('Including loaders...'),
            Phake::verify($this->_isolator)->require('foo'),
            Phake::verify($this->_isolator)->require('bar'),
            Phake::verify($output)->writeln('Generating validator classes...'),
            Phake::verify($this->_generator)->generate(
                'baz',
                array(
                    'qux',
                    'doom',
                )
            ),
            Phake::verify($output)->writeln('Deploying Typhoon...'),
            Phake::verify($this->_deploymentManager)->deploy('baz'),
            Phake::verify($output)->writeln('Done.')
        );
    }
}
