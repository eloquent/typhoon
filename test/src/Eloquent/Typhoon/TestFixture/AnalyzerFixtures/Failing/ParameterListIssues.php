<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\TestFixture\AnalyzerFixtures\Failing;

use Typhoon\TypeCheck;

class ParameterListIssues
{
    public function __construct()
    {
        $this->typeCheck = TypeCheck::get(__CLASS__, func_get_args());
    }

    /**
     * @param mixed $one,...
     */
    public function definedParameterVariableLength($one)
    {
        $this->typeCheck->definedParameterVariableLength(func_get_args());
    }

    /**
     * @param mixed $one
     * @param mixed &$two
     */
    public function documentedParameterByReferenceMismatch(&$one, $two)
    {
        $this->typeCheck->documentedParameterByReferenceMismatch(func_get_args());
    }

    /**
     * @param mixed $one
     * @param mixed $two
     */
    public function documentedParameterNameMismatch($two, $one)
    {
        $this->typeCheck->documentedParameterNameMismatch(func_get_args());
    }

    /**
     * @param array $one
     * @param string $two
     */
    public function documentedParameterTypeMismatch($one, array $two)
    {
        $this->typeCheck->documentedParameterTypeMismatch(func_get_args());
    }

    /**
     * @param array $one
     * @param string $two
     */
    public function documentedParameterUndefined()
    {
        $this->typeCheck->documentedParameterUndefined(func_get_args());
    }

    public function undocumentedParameter($one, $two)
    {
        $this->typeCheck->undocumentedParameter(func_get_args());
    }

    private $typeCheck;
}
