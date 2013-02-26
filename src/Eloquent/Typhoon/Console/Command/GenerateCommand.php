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

use Eloquent\Typhoon\Generator\ProjectValidatorGenerator;
use Eloquent\Typhoon\TypeCheck\TypeCheck;
use Icecave\Isolator\Isolator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateCommand extends Command
{
    /**
     * @param ProjectValidatorGenerator|null $generator
     * @param Isolator|null                  $isolator
     */
    public function __construct(
        ProjectValidatorGenerator $generator = null,
        Isolator $isolator = null
    ) {
        $this->typeCheck = TypeCheck::get(__CLASS__, func_get_args());
        if (null === $generator) {
            $generator = new ProjectValidatorGenerator;
        }

        $this->generator = $generator;

        parent::__construct($isolator);
    }

    /**
     * @return ProjectValidatorGenerator
     */
    public function generator()
    {
        $this->typeCheck->generator(func_get_args());

        return $this->generator;
    }

    protected function configure()
    {
        $this->typeCheck->configure(func_get_args());

        $this->setName('generate');
        $this->setDescription('Generates Typhoon classes for a project.');

        $this->addArgument(
            'path',
            InputArgument::OPTIONAL,
            'The path to the root of the project.',
            '.'
        );
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->typeCheck->execute(func_get_args());

        $dummyMode = TypeCheck::dummyMode();
        TypeCheck::setDummyMode(true);

        $this->isolator->chdir($input->getArgument('path'));

        $output->setVerbosity(OutputInterface::VERBOSITY_VERBOSE);
        $configuration = $this->getApplication()->configurationReader()->read(null, true);
        $this->includeLoaders($configuration, $output);

        $output->writeln('<info>Generating classes...</info>');
        $this->generator()->generate($configuration);

        $output->writeln('<info>Done.</info>');

        TypeCheck::setDummyMode($dummyMode);
    }

    private $generator;
    private $typeCheck;
}
