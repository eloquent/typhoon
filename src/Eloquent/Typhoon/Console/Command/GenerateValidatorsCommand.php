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

use Eloquent\Typhoon\Generator\ProjectValidatorGenerator;
use Icecave\Isolator\Isolator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateValidatorsCommand extends Command
{
    /**
     * @param ProjectValidatorGenerator|null $generator
     * @param Isolator|null $isolator
     */
    public function __construct(
        ProjectValidatorGenerator $generator = null,
        Isolator $isolator = null
    ) {
        if (null === $generator) {
            $generator = new ProjectValidatorGenerator;
        }

        $this->generator = $generator;
        $this->isolator = Isolator::get($isolator);

        parent::__construct();
    }

    /**
     * @return ProjectValidatorGenerator
     */
    public function generator()
    {
        return $this->generator;
    }

    protected function configure()
    {
        $this->setName('generate:validators');
        $this->setDescription(
            'Generates Typhoon validator classes for a given directory.'
        );

        $this->addArgument(
            'output-path',
            InputArgument::REQUIRED,
            'The path in which to create the validator classes.'
        );
        $this->addArgument(
            'class-path',
            InputArgument::REQUIRED | InputArgument::IS_ARRAY,
            'One or more paths containing the source classes.'
        );

        $this->addOption(
            'loader-path',
            'l',
            InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
            'Path to one or more scripts used to load the source classes in the given path.',
            array(
                './vendor/autoload.php',
            )
        );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->setVerbosity(OutputInterface::VERBOSITY_VERBOSE);

        $output->writeln('Including loaders...');
        foreach ($input->getOption('loader-path') as $path){
            $this->isolator->require($path);
        }

        $output->writeln('Generating validator classes...');
        $this->generator->generate(
            $input->getArgument('output-path'),
            $input->getArgument('class-path')
        );

        $output->writeln('Done.');
    }

    private $generator;
    private $isolator;
}