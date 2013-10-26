<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\CodeAnalysis\Issue\Exception;

use Eloquent\Typhoon\CodeAnalysis\Issue\IssueInterface;
use Eloquent\Typhoon\CodeAnalysis\Issue\IssueRenderer;
use Eloquent\Typhoon\TypeCheck\TypeCheck;
use Exception;
use LogicException;

final class UnfixableIssueException extends LogicException
{
    /**
     * @param IssueInterface     $issue
     * @param Exception|null     $previous
     * @param IssueRenderer|null $renderer
     */
    public function __construct(
        IssueInterface $issue,
        Exception $previous = null,
        IssueRenderer $renderer = null
    ) {
        $this->typeCheck = TypeCheck::get(__CLASS__, func_get_args());
        if (null === $renderer) {
            $renderer = new IssueRenderer;
        }

        $this->issue = $issue;

        parent::__construct(
            sprintf(
                "Unable to automatically fix '%s'.",
                $this->issue()->accept($renderer)
            ),
            0,
            $previous
        );
    }

    /**
     * @return IssueInterface
     */
    public function issue()
    {
        $this->typeCheck->issue(func_get_args());

        return $this->issue;
    }

    private $issue;
    private $typeCheck;
}
