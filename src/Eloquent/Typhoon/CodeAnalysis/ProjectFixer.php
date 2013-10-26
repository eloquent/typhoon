<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\CodeAnalysis;

use Eloquent\Typhoon\Configuration\Configuration;
use Eloquent\Typhoon\TypeCheck\TypeCheck;

class ProjectFixer
{
    /**
     * @param ProjectAnalyzer|null  $analyzer
     * @param Issue\IssueFixer|null $issueFixer
     */
    public function __construct(
        ProjectAnalyzer $analyzer = null,
        Issue\IssueFixer $issueFixer = null
    ) {
        $this->typeCheck = TypeCheck::get(__CLASS__, func_get_args());
        if (null === $analyzer) {
            $analyzer = new ProjectAnalyzer;
        }
        if (null === $issueFixer) {
            $issueFixer = new Issue\IssueFixer;
        }

        $this->analyzer = $analyzer;
        $this->issueFixer = $issueFixer;
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
     * @return Issue\IssueFixer
     */
    public function issueFixer()
    {
        $this->typeCheck->issueFixer(func_get_args());

        return $this->issueFixer;
    }

    /**
     * @param Configuration      $configuration
     * @param array<string>|null $sourcePaths
     *
     * @return array<string>
     */
    public function fix(Configuration $configuration, array $sourcePaths = null)
    {
        $this->typeCheck->fix(func_get_args());

        return $this->issueFixer()->fix(
            $this->analyzer()->analyze($configuration, $sourcePaths)
        );
    }

    private $analyzer;
    private $issueFixer;
    private $typeCheck;
}
