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

        $errorLinesByClass = array();

        if (count($result->classesMissingConstructorCall()) > 0) {
            $this->addClassDefinitionList(
                $errorLinesByClass,
                $result->classesMissingConstructorCall(),
                'Incorrect or missing constructor initialization.'
            );
        }
        if (count($result->classesMissingProperty()) > 0) {
            $this->addClassDefinitionList(
                $errorLinesByClass,
                $result->classesMissingProperty(),
                'Incorrect or missing property definition.'
            );
        }
        if (count($result->methodsMissingCall()) > 0) {
            $this->addMethodDefinitionList(
                $errorLinesByClass,
                $result->methodsMissingCall(),
                'Incorrect or missing type check call in method %s().'
            );
        }

        $errorLines = array(
            '[Problems detected]',
        );
        foreach ($errorLinesByClass as $class => $classErrorLines) {
            $errorLines[] = '';
            $errorLines[] = sprintf('  [%s]', $class);

            foreach ($classErrorLines as $errorLine) {
                $errorLines[] = sprintf('    - %s', $errorLine);
            }
        }

        return $this->getHelperSet()
            ->get('formatter')
            ->formatBlock($errorLines, 'error', true)
        ;
    }

    /**
     * @param array<string,array<string>> &$errorLinesByClass
     * @param array<ClassDefinition>      $classDefinitions
     * @param string                      $label
     */
    protected function addClassDefinitionList(
        array &$errorLinesByClass,
        array $classDefinitions,
        $label
    ) {
        $this->typeCheck->addClassDefinitionList(func_get_args());

        foreach ($classDefinitions as $classDefinition) {
            $relativeClassName = $classDefinition->className()->toRelative()->string();
            $errorLinesByClass[$relativeClassName][] = $label;
        }
    }

    /**
     * @param array<string,array<string>>                    &$errorLinesByClass
     * @param array<tuple<ClassDefinition,MethodDefinition>> $tuples
     * @param string                                         $label
     */
    protected function addMethodDefinitionList(
        array &$errorLinesByClass,
        array $tuples,
        $label
    ) {
        $this->typeCheck->addMethodDefinitionList(func_get_args());

        foreach ($tuples as $tuple) {
            $relativeClassName = $tuple[0]->className()->toRelative()->string();
            $errorLinesByClass[$relativeClassName][] = sprintf(
                $label,
                $tuple[1]->name()
            );
        }
    }

    private $generator;
    private $isolator;
    private $typeCheck;
}
