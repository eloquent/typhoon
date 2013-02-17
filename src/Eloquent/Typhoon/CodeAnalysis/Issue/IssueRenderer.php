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

use Eloquent\Typhoon\TypeCheck\TypeCheck;

class IssueRenderer implements IssueVisitorInterface
{
    public function __construct()
    {
        $this->typeCheck = TypeCheck::get(__CLASS__, func_get_args());
    }

    /**
     * @param ClassRelated\MissingConstructorCall $issue
     *
     * @return string
     */
    public function visitMissingConstructorCall(ClassRelated\MissingConstructorCall $issue)
    {
        $this->typeCheck->visitMissingConstructorCall(func_get_args());

        return 'Incorrect or missing constructor initialization.';
    }

    /**
     * @param ClassRelated\MissingProperty $issue
     *
     * @return string
     */
    public function visitMissingProperty(ClassRelated\MissingProperty $issue)
    {
        $this->typeCheck->visitMissingProperty(func_get_args());

        return 'Incorrect or missing property definition.';
    }

    /**
     * @param MethodRelated\InadmissibleMethodCall $issue
     *
     * @return string
     */
    public function visitInadmissibleMethodCall(MethodRelated\InadmissibleMethodCall $issue)
    {
        $this->typeCheck->visitInadmissibleMethodCall(func_get_args());

        return sprintf(
            'Type check call should not be present in method %s().',
            $issue->methodDefinition()->name()
        );
    }

    /**
     * @param MethodRelated\MissingMethodCall $issue
     *
     * @return string
     */
    public function visitMissingMethodCall(MethodRelated\MissingMethodCall $issue)
    {
        $this->typeCheck->visitMissingMethodCall(func_get_args());

        return sprintf(
            'Incorrect or missing type check call in method %s().',
            $issue->methodDefinition()->name()
        );
    }

    private $typeCheck;
}
