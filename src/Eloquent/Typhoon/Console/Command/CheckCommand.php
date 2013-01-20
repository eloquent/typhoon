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

use Eloquent\Typhoon\ClassMapper\ClassDefinition;
use Eloquent\Typhoon\ClassMapper\MethodDefinition;
use Eloquent\Typhoon\CodeAnalysis\AnalysisResult;
use Eloquent\Typhoon\CodeAnalysis\ProjectAnalyzer;
use Eloquent\Typhoon\TypeCheck\TypeCheck;
use Icecave\Isolator\Isolator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CheckCommand extends Command
{
    /**
     * @param ProjectAnalyzer|null $analyzer
     */
    public function __construct(
        ProjectAnalyzer $analyzer = null
    ) {
        $this->typeCheck = TypeCheck::get(__CLASS__, func_get_args());
        if (null === $analyzer) {
            $analyzer = new ProjectAnalyzer;
        }

        $this->analyzer = $analyzer;

        parent::__construct();
    }

    /**
     * @return ProjectAnalyzer
     */
    public function analyzer()
    {
        $this->typeCheck->analyzer(func_get_args());

        return $this->analyzer;
    }

    protected function configure()
    {
        $this->typeCheck->configure(func_get_args());

        $this->setName('check');
        $this->setDescription('Checks for correct Typhoon setup within a project.');
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

        $output->setVerbosity(OutputInterface::VERBOSITY_VERBOSE);
        $configuration = $this->getApplication()->configurationReader()->read(null, true);

        $output->writeln('<info>Checking for correct Typhoon setup...</info>');
        $result = $this->analyzer()->analyze($configuration);

        if ($result->isSuccessful()) {
            $output->writeln('<info>No problems detected.</info>');

            return 0;
        }

        $output->writeln('');
        $output->writeln($this->generateErrorBlock($result));

        return 1;
    }

    /**
     * @param AnalysisResult $result
     *
     * @return string
     */
    protected function generateErrorBlock(AnalysisResult $result)
    {
        $this->typeCheck->generateErrorBlock(func_get_args());

        $errorLines = array(
            '[Problems detected]',
            '',
        );
        if (count($result->classesMissingConstructorCall()) > 0) {
            $this->addClassDefinitionList(
                $errorLines,
                $result->classesMissingConstructorCall(),
                'Classes that do not initialize Typhoon correctly in their constructor'
            );
        }
        if (count($result->classesMissingProperty()) > 0) {
            $this->addClassDefinitionList(
                $errorLines,
                $result->classesMissingProperty(),
                'Classes that do not have a correctly defined typeCheck property'
            );
        }
        if (count($result->methodsMissingCall()) > 0) {
            $this->addMethodDefinitionList(
                $errorLines,
                $result->methodsMissingCall(),
                'Methods that do not have a correct typeCheck call'
            );
        }

        return $this->getHelperSet()
            ->get('formatter')
            ->formatBlock($errorLines, 'error', true)
        ;
    }

    /**
     * @param array<string>          &$errorLines
     * @param array<ClassDefinition> $classDefinitions
     * @param string                 $label
     */
    protected function addClassDefinitionList(
        array &$errorLines,
        array $classDefinitions,
        $label
    ) {
        $this->typeCheck->addClassDefinitionList(func_get_args());

        if (count($classDefinitions) > 0) {
            $errorLines[] = sprintf('  %s:', $label);

            foreach ($classDefinitions as $classDefinition) {
                $errorLines[] = sprintf(
                    '    - %s',
                    $classDefinition->className()->toRelative()->string()
                );
            }
        }
    }

    /**
     * @param array<string>                                  &$errorLines
     * @param array<tuple<ClassDefinition,MethodDefinition>> $tuples
     * @param string                                         $label
     */
    protected function addMethodDefinitionList(
        array &$errorLines,
        array $tuples,
        $label
    ) {
        $this->typeCheck->addMethodDefinitionList(func_get_args());

        if (count($tuples) > 0) {
            $errorLines[] = sprintf('  %s:', $label);

            foreach ($tuples as $tuple) {
                $errorLines[] = sprintf(
                    '    - %s::%s()',
                    $tuple[0]->className()->toRelative()->string(),
                    $tuple[1]->name()
                );
            }
        }
    }

    private $generator;
    private $isolator;
    private $typeCheck;
}
