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

use Eloquent\Typhoon\CodeAnalysis\ProjectFixer;
use Eloquent\Typhoon\TypeCheck\TypeCheck;
use Icecave\Isolator\Isolator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FixCommand extends Command
{
    /**
     * @param ProjectFixer|null    $fixer
     * @param Isolator|null        $isolator
     */
    public function __construct(
        ProjectFixer $fixer = null,
        Isolator $isolator = null
    ) {
        $this->typeCheck = TypeCheck::get(__CLASS__, func_get_args());
        if (null === $fixer) {
            $fixer = new ProjectFixer;
        }

        $this->fixer = $fixer;

        parent::__construct($isolator);
    }

    /**
     * @return ProjectFixer
     */
    public function fixer()
    {
        $this->typeCheck->fixer(func_get_args());

        return $this->fixer;
    }

    protected function configure()
    {
        $this->typeCheck->configure(func_get_args());

        $this->setName('fix');
        $this->setDescription('Automatically corrects Typhoon setup issues within a project.');

        $this->addArgument(
            'project-path',
            InputArgument::OPTIONAL,
            'The path to the root of the project.',
            '.'
        );
        $this->addArgument(
            'source-path',
            InputArgument::OPTIONAL | InputArgument::IS_ARRAY,
            'One or more source paths to fix. Defaults to configured source paths.'
        );
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return integer
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->typeCheck->execute(func_get_args());

        $sourcePaths = $input->getArgument('source-path');
        if (array() === $sourcePaths) {
            $sourcePaths = null;
        }

        $this->isolator->chdir($input->getArgument('project-path'));

        $configuration = $this->getApplication()->configurationReader()->read(null, true);
        $this->includeLoaders($configuration, $output);

        $output->writeln('<info>Fixing Typhoon setup...</info>');
        $changedPaths = $this->fixer()->fix($configuration, $sourcePaths);
        $changeCount = count($this->fixer()->changedPaths());

        if ($changeCount < 1) {
            $output->writeln('<info>No files modified.</info>');

            return 0;
        }

        $output->writeln(sprintf(
            '<info>Modified %d files.</info>',
            $changeCount
        ));
        foreach ($changedPaths as $path) {
            $output->writeln(sprintf('- %s', $path));
        }

        return 1;
    }

    private $fixer;
    private $typeCheck;
}
