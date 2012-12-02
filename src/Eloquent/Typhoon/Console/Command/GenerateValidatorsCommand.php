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
use Eloquent\Typhoon\Validators\Typhoon;
use Icecave\Isolator\Isolator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateValidatorsCommand extends Command
{
    /**
     * @param ProjectValidatorGenerator|null $generator
     * @param Isolator|null                  $isolator
     */
    public function __construct(
        ProjectValidatorGenerator $generator = null,
        Isolator $isolator = null
    ) {
        $this->typhoon = Typhoon::get(__CLASS__, func_get_args());
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
        $this->typhoon->generator(func_get_args());

        return $this->generator;
    }

    protected function configure()
    {
        $this->typhoon->configure(func_get_args());

        $this->setName('generate:validators');
        $this->setDescription(
            'Generates Typhoon validator classes for a given directory.'
        );
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->typhoon->execute(func_get_args());

        $output->setVerbosity(OutputInterface::VERBOSITY_VERBOSE);
        $configuration = $this->getApplication()->configurationReader()->read(
            null,
            true
        );

        $output->writeln('Including loaders...');
        foreach ($configuration->loaderPaths() as $path) {
            $this->isolator->require($path);
        }

        $output->writeln('Generating validator classes...');
        $this->generator()->generate($configuration);

        $output->writeln('Done.');
    }

    private $generator;
    private $isolator;
    private $typhoon;
}
