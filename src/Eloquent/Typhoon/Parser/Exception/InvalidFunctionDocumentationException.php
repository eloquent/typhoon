<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Parser\Exception;

use Eloquent\Cosmos\ClassName;
use Eloquent\Typhoon\TypeCheck\TypeCheck;
use Exception;
use LogicException;

final class InvalidFunctionDocumentationException extends LogicException
{
    /**
     * @param ClassName|null $className
     * @param string         $functionName
     * @param Exception|null $previous
     */
    public function __construct(
        ClassName $className = null,
        $functionName,
        Exception $previous = null
    ) {
        $this->typeCheck = TypeCheck::get(__CLASS__, func_get_args());
        $this->className = $className;
        $this->functionName = $functionName;

        if (null === $className) {
            $message = sprintf(
                'Invalid param tags found in the documentation for function %s().',
                $this->functionName()
            );
        } else {
            $message = sprintf(
                'Invalid param tags found in the documentation for method %s::%s().',
                $this->className()->string(),
                $this->functionName()
            );
        }

        parent::__construct($message, 0, $previous);
    }

    /**
     * @return ClassName|null
     */
    public function className()
    {
        $this->typeCheck->className(func_get_args());

        return $this->className;
    }

    /**
     * @return string
     */
    public function functionName()
    {
        $this->typeCheck->functionName(func_get_args());

        return $this->functionName;
    }

    private $className;
    private $functionName;
    private $typeCheck;
}
