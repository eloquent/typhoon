<?php // @codeCoverageIgnoreStart

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\CodeAnalysis\Issue;

interface IssueInterface
{
    /**
     * @return IssueSeverity
     */
    public function severity();

    /**
     * @param IssueVisitorInterface $visitor
     *
     * @return mixed
     */
    public function accept(IssueVisitorInterface $visitor);
}
