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

class IssueRenderer implements IssueVisitor
{
    public function __construct()
    {
        $this->typeCheck = TypeCheck::get(__CLASS__, func_get_args());
    }

    /**
     * @param MissingConstructorCall $issue
     *
     * @return string
     */
    public function visitMissingConstructorCall(MissingConstructorCall $issue)
    {
        $this->typeCheck->visitMissingConstructorCall(func_get_args());

        return 'Incorrect or missing constructor initialization.';
    }

    /**
     * @param MissingMethodCall $issue
     *
     * @return string
     */
    public function visitMissingMethodCall(MissingMethodCall $issue)
    {
        $this->typeCheck->visitMissingMethodCall(func_get_args());

        return sprintf(
            'Incorrect or missing type check call in method %s().',
            $issue->methodDefinition()->name()
        );
    }

    /**
     * @param MissingProperty $issue
     *
     * @return string
     */
    public function visitMissingProperty(MissingProperty $issue)
    {
        $this->typeCheck->visitMissingProperty(func_get_args());

        return 'Incorrect or missing property definition.';
    }

    private $typeCheck;
}
