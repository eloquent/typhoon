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

use Eloquent\Typhoon\ClassMapper\ClassMapper;
use Eloquent\Typhoon\Generator\ValidatorClassGenerator;
use Icecave\Isolator\Isolator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateValidatorsCommand extends Command
{
    public function __construct(
        ClassMapper $classMapper = null,
        ValidatorClassGenerator $generator = null,
        Isolator $isolator = null
    ) {
        if (null === $classMapper) {
            $classMapper = new ClassMapper;
        }
        if (null === $generator) {
            $generator = new ValidatorClassGenerator;
        }

        $this->classMapper = $classMapper;
        $this->generator = $generator;
        $this->isolator = Isolator::get($isolator);

        parent::__construct();
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

        $this->includeLoaders($input, $output);
        $this->generateValidators($input, $output);

        $output->writeln('Done.');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function includeLoaders(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Including loaders...');

        foreach ($input->getOption('loader-path') as $path){
            require $path;
        }
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function generateValidators(InputInterface $input, OutputInterface $output)
    {
        $classMap = $this->buildClassMap($input, $output);

        $output->writeln('Generating validator classes...');
        foreach ($classMap as $classDefinition) {
            $source = $this->generator->generate(
                $classDefinition,
                $namespaceName,
                $className
            );

            $path =
                $input->getArgument('output-path').'/'.
                $this->PSRPath($namespaceName, $className)
            ;

            $parentPath = dirname($path);
            if (!$this->isolator->is_dir($parentPath)) {
                $this->isolator->mkdir($parentPath, 0777, true);
            }

            $this->isolator->file_put_contents(
                $path,
                $source
            );
        }
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return array<ClassDefinition>
     */
    protected function buildClassMap(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Scanning for classes...');

        $classMap = array();
        foreach ($input->getArgument('class-path') as $classPath) {
            $classMap = array_merge(
                $this->classMapper->classesByDirectory(
                    $classPath
                ),
                $classMap
            );
        }

        return $classMap;
    }

    /**
     * @param string $namespaceName
     * @param string $className
     *
     * @return string
     */
    protected function PSRPath($namespaceName, $className)
    {
        return
            str_replace('\\', '/', $namespaceName).
            '/'.
            str_replace('_', '/', $className).
            '.php'
        ;
    }

    private $classMapper;
    private $generator;
    private $isolator;
}
