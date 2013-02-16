<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\CodeAnalysis\Issue\ClassRelated;

use Eloquent\Typhoon\CodeAnalysis\Issue\Issue;

interface ClassRelatedIssue extends Issue
{
    /**
     * @return \Eloquent\Typhoon\ClassMapper\ClassDefinition
     */
    public function classDefinition();
}
