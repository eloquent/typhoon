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

interface IssueVisitor
{
    /**
     * @param InadmissibleMethodCall $issue
     *
     * @return mixed
     */
    public function visitInadmissibleMethodCall(InadmissibleMethodCall $issue);

    /**
     * @param MissingConstructorCall $issue
     *
     * @return mixed
     */
    public function visitMissingConstructorCall(MissingConstructorCall $issue);

    /**
     * @param MissingMethodCall $issue
     *
     * @return mixed
     */
    public function visitMissingMethodCall(MissingMethodCall $issue);

    /**
     * @param MissingProperty $issue
     *
     * @return mixed
     */
    public function visitMissingProperty(MissingProperty $issue);

    /**
     * @param UnserializeMethod $issue
     *
     * @return mixed
     */
    public function visitUnserializeMethod(UnserializeMethod $issue);
}
