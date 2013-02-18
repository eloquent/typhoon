<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Generator\ParameterListMerge\Exception;

use Eloquent\Typhoon\CodeAnalysis\Issue\IssueInterface;
use Eloquent\Typhoon\CodeAnalysis\Issue\IssueRenderer;
use Eloquent\Typhoon\CodeAnalysis\Issue\ClassRelatedIssueInterface;
use Eloquent\Typhoon\CodeAnalysis\Issue\MethodRelatedIssueInterface;
use Eloquent\Typhoon\TypeCheck\TypeCheck;
use Exception;
use LogicException;

final class ParameterListMergeException extends LogicException
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
        $this->renderer = $renderer;

        parent::__construct(
            $this->renderMessage(),
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

    /**
     * @return IssueRenderer
     */
    public function renderer()
    {
        $this->typeCheck->renderer(func_get_args());

        return $this->renderer;
    }

    /**
     * @return string
     */
    protected function renderMessage()
    {
        $this->typeCheck->renderMessage(func_get_args());

        $message = $this->issue()->accept($this->renderer());
        if ($this->issue() instanceof MethodRelatedIssueInterface) {
            $message = sprintf(
                'Error in method %s::%s(): %s',
                $this->issue()->classDefinition()->className()->string(),
                $this->issue()->methodDefinition()->name(),
                $message
            );
        } elseif ($this->issue() instanceof ClassRelatedIssueInterface) {
            $message = sprintf(
                'Error in class %s: %s',
                $this->issue()->classDefinition()->className()->string(),
                $message
            );
        }

        return $message;
    }

    private $issue;
    private $renderer;
    private $typeCheck;
}
