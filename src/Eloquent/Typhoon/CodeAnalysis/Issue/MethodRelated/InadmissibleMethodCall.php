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

use Eloquent\Typhoon\CodeAnalysis\Issue\AbstractMethodIssue;
use Eloquent\Typhoon\CodeAnalysis\Issue\IssueVisitorInterface;
use Eloquent\Typhoon\TypeCheck\TypeCheck;

class InadmissibleMethodCall extends AbstractMethodIssue
{
    /**
     * @param IssueVisitorInterface $visitor
     *
     * @return mixed
     */
    public function accept(IssueVisitorInterface $visitor)
    {
        TypeCheck::get(__CLASS__)->accept(func_get_args());

        return $visitor->visitInadmissibleMethodCall($this);
    }
}
