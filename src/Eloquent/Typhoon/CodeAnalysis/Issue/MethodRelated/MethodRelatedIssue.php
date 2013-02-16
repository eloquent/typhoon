<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\CodeAnalysis\Issue\MethodRelated;

use Eloquent\Typhoon\CodeAnalysis\Issue\ClassRelated\ClassRelatedIssue;

interface MethodRelatedIssue extends ClassRelatedIssue
{
    /**
     * @return \Eloquent\Typhoon\ClassMapper\MethodDefinition
     */
    public function methodDefinition();
}
