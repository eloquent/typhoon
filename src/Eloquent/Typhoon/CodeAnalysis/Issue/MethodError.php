<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\CodeAnalysis\Issue;

use Eloquent\Typhoon\TypeCheck\TypeCheck;

abstract class MethodError extends MethodIssue
{
    /**
     * @return IssueSeverity
     */
    public function severity()
    {
        TypeCheck::get(__CLASS__)->severity(func_get_args());

        return IssueSeverity::ERROR();
    }
}
