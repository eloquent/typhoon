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

use Eloquent\Typhoon\CodeAnalysis\AnalysisResult;
use Eloquent\Typhoon\CodeAnalysis\Issue\Issue;
use Eloquent\Typhoon\CodeAnalysis\Issue\IssueRenderer;
use Eloquent\Typhoon\CodeAnalysis\Issue\IssueSeverity;
use Eloquent\Typhoon\CodeAnalysis\ProjectAnalyzer;
use Eloquent\Typhoon\TypeCheck\TypeCheck;
use Icecave\Isolator\Isolator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CheckCommand extends Command
{
    /**
     * @param ProjectAnalyzer|null $analyzer
     * @param IssueRenderer|null   $issueRenderer
     * @param Isolator|null        $isolator
     */
    public function __construct(
        ProjectAnalyzer $analyzer = null,
        IssueRenderer $issueRenderer = null,
        Isolator $isolator = null
    ) {
        $this->typeCheck = TypeCheck::get(__CLASS__, func_get_args());
        if (null === $analyzer) {
            $analyzer = new ProjectAnalyzer;
        }
        if (null === $issueRenderer) {
            $issueRenderer = new IssueRenderer;
        }

        $this->analyzer = $analyzer;
        $this->issueRenderer = $issueRenderer;

        parent::__construct($isolator);
    }

    /**
     * @return ProjectAnalyzer
     */
    public function analyzer()
    {
        $this->typeCheck->analyzer(func_get_args());

        return $this->analyzer;
    }

    /**
     * @return IssueRenderer
     */
    public function issueRenderer()
    {
        $this->typeCheck->issueRenderer(func_get_args());

        return $this->issueRenderer;
    }

    protected function configure()
    {
        $this->typeCheck->configure(func_get_args());

        $this->setName('check');
        $this->setDescription('Checks for correct Typhoon setup within a project.');

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
     *
     * @return integer
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->typeCheck->execute(func_get_args());

        $this->isolator->chdir($input->getArgument('path'));

        $output->setVerbosity(OutputInterface::VERBOSITY_VERBOSE);
        $configuration = $this->getApplication()->configurationReader()->read(null, true);
        $this->includeLoaders($configuration, $output);

        $output->writeln('<info>Checking for correct Typhoon setup...</info>');
        $result = $this->analyzer()->analyze($configuration);

        if (count($result->issues()) < 1) {
            $output->writeln('<info>No problems detected.</info>');
        } else {
            if (count($result->issuesBySeverity(IssueSeverity::ERROR())) > 0) {
                $output->writeln('');
                $output->writeln($this->generateErrorBlock($result));
            }
            if (count($result->issuesBySeverity(IssueSeverity::WARNING())) > 0) {
                $output->writeln('');
                $output->writeln($this->generateWarningBlock($result));
            }
        }

        if ($result->isError()) {
            return 1;
        }

        return 0;
    }

    /**
     * @param AnalysisResult $result
     *
     * @return string
     */
    protected function generateErrorBlock(AnalysisResult $result)
    {
        $this->typeCheck->generateErrorBlock(func_get_args());

        return $this->generateBlock(
            'Problems detected',
            'error',
            $result->issuesBySeverityByClass(IssueSeverity::ERROR())
        );
    }

    /**
     * @param AnalysisResult $result
     *
     * @return string
     */
    protected function generateWarningBlock(AnalysisResult $result)
    {
        $this->typeCheck->generateWarningBlock(func_get_args());

        return $this->generateBlock(
            'Potential problems detected',
            'comment',
            $result->issuesBySeverityByClass(IssueSeverity::WARNING())
        );
    }

    /**
     * @param string                     $label
     * @param string                     $blockStyle
     * @param array<string,array<Issue>> $issues
     *
     * @return string
     */
    protected function generateBlock($label, $blockStyle, array $issues)
    {
        $this->typeCheck->generateBlock(func_get_args());

        $errorLines = array(
            sprintf('[%s]', $label),
        );

        foreach ($issues as $class => $classIssues) {
            $errorLines[] = '';
            $errorLines[] = sprintf('  [%s]', $class);

            foreach ($classIssues as $issue) {
                $errorLines[] = sprintf(
                    '    - %s',
                    $issue->accept($this->issueRenderer())
                );
            }
        }

        return $this->getHelperSet()
            ->get('formatter')
            ->formatBlock($errorLines, $blockStyle, true)
        ;
    }

    private $analyzer;
    private $issueRenderer;
    private $typeCheck;
}
