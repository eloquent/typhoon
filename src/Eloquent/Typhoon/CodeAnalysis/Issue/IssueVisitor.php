<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\CodeAnalysis\Issue;

interface IssueVisitor
{
    /**
     * @param ClassRelated\MissingConstructorCall $issue
     *
     * @return mixed
     */
    public function visitMissingConstructorCall(ClassRelated\MissingConstructorCall $issue);

    /**
     * @param ClassRelated\MissingProperty $issue
     *
     * @return mixed
     */
    public function visitMissingProperty(ClassRelated\MissingProperty $issue);

    /**
     * @param MethodRelated\InadmissibleMethodCall $issue
     *
     * @return mixed
     */
    public function visitInadmissibleMethodCall(MethodRelated\InadmissibleMethodCall $issue);

    /**
     * @param MethodRelated\MissingMethodCall $issue
     *
     * @return mixed
     */
    public function visitMissingMethodCall(MethodRelated\MissingMethodCall $issue);
}
