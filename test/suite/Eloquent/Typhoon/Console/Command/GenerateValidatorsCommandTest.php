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
use Eloquent\Typhoon\Configuration\Configuration;
use Eloquent\Typhoon\TestCase\MultiGenerationTestCase;
use Phake;
use Symfony\Component\Console\Input\InputDefinition;

class GenerateValidatorsCommandTest extends MultiGenerationTestCase
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
            __NAMESPACE__.'\GenerateValidatorsCommand',
            $this->_generator,
            $this->_isolator
        );
        Phake::when($this->_command)->getApplication()->thenReturn($this->_application);
    }

    public function testConstructor()
    {
        $this->assertSame($this->_generator, $this->_command->generator());
    }

    public function testConstructorDefaults()
    {
        $command = new GenerateValidatorsCommand;

        $this->assertInstanceOf(
            'Eloquent\Typhoon\Generator\ProjectValidatorGenerator',
            $command->generator()
        );
    }

    public function testConfigure()
    {
        $this->assertSame('generate:validators', $this->_command->getName());
        $this->assertSame(
            'Generates Typhoon validator classes for a given directory.',
            $this->_command->getDescription()
        );
        $this->assertEquals(new InputDefinition, $this->_command->getDefinition());
    }

    public function testExecute()
    {
        $input = Phake::mock('Symfony\Component\Console\Input\InputInterface');
        $output = Phake::mock('Symfony\Component\Console\Output\OutputInterface');
        Liberator::liberate($this->_command)->execute($input, $output);

        Phake::inOrder(
            Phake::verify($output)->writeln('Including loaders...'),
            Phake::verify($this->_isolator)->require('foo'),
            Phake::verify($this->_isolator)->require('bar'),
            Phake::verify($output)->writeln('Generating validator classes...'),
            Phake::verify($this->_generator)->generate(
                $this->identicalTo($this->_configuration)
            ),
            Phake::verify($output)->writeln('Done.')
        );
    }
}
