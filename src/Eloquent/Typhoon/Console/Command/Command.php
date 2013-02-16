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

use Eloquent\Typhoon\Configuration\Configuration;
use Eloquent\Typhoon\TypeCheck\TypeCheck;
use Icecave\Isolator\Isolator;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Output\OutputInterface;

abstract class Command extends SymfonyCommand
{
    /**
     * @param Isolator|null $isolator
     */
    public function __construct(Isolator $isolator = null)
    {
        $this->typeCheck = TypeCheck::get(__CLASS__, func_get_args());
        $this->isolator = Isolator::get($isolator);

        parent::__construct();
    }

    /**
     * @param Configuration   $configuration
     * @param OutputInterface $output
     */
    protected function includeLoaders(
        Configuration $configuration,
        OutputInterface $output
    ) {
        $this->typeCheck->includeLoaders(func_get_args());

        $output->writeln('<info>Including loaders...</info>');
        foreach ($configuration->loaderPaths() as $path) {
            $output->writeln(sprintf('  - %s', $path));
            $this->isolator->require($path);
        }
    }

    protected $isolator;
    private $typeCheck;
}
