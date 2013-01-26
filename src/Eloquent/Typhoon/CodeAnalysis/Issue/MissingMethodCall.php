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

class MissingMethodCall extends MethodError
{
    /**
     * @param IssueVisitor $visitor
     *
     * @return mixed
     */
    public function accept(IssueVisitor $visitor)
    {
        TypeCheck::get(__CLASS__)->accept(func_get_args());

        return $visitor->visitMissingMethodCall($this);
    }
}
