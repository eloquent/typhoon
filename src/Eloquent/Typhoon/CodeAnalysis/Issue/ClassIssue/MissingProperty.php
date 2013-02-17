<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\CodeAnalysis\Issue\ClassIssue;

use Eloquent\Typhoon\CodeAnalysis\Issue\AbstractClassRelatedIssue;
use Eloquent\Typhoon\CodeAnalysis\Issue\ClassIssueInterface;
use Eloquent\Typhoon\CodeAnalysis\Issue\IssueVisitorInterface;
use Eloquent\Typhoon\TypeCheck\TypeCheck;

class MissingProperty extends AbstractClassRelatedIssue implements ClassIssueInterface
{
    /**
     * @param IssueVisitorInterface $visitor
     *
     * @return mixed
     */
    public function accept(IssueVisitorInterface $visitor)
    {
        TypeCheck::get(__CLASS__)->accept(func_get_args());

        return $visitor->visitMissingProperty($this);
    }
}
