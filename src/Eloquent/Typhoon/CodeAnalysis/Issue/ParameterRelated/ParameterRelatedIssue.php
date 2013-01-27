<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\CodeAnalysis\Issue\ParameterRelated;

use Eloquent\Typhoon\CodeAnalysis\Issue\MethodRelated\MethodRelatedIssue;

interface ParameterRelatedIssue extends MethodRelatedIssue
{
    /**
     * @return \Eloquent\Typhoon\Parameter\Parameter
     */
    public function parameterDefinition();
}
