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

use Eloquent\Cosmos\ClassName;
use Eloquent\Typhoon\TypeCheck\TypeCheck;
use Exception;
use LogicException;

final class DefinedParameterVariableLengthException extends LogicException
{
    /**
     * @param ClassName|null $className
     * @param string         $functionName
     * @param string         $parameterName
     * @param Exception|null $previous
     */
    public function __construct(
        ClassName $className = null,
        $functionName,
        $parameterName,
        Exception $previous = null
    ) {
        $this->typeCheck = TypeCheck::get(__CLASS__, func_get_args());
        $this->className = $className;
        $this->functionName = $functionName;
        $this->parameterName = $parameterName;

        if (null === $className) {
            $message = sprintf(
                'Variable-length parameter $%s should only be documented, not defined in function %s().',
                $this->parameterName(),
                $this->functionName()
            );
        } else {
            $message = sprintf(
                'Variable-length parameter $%s should only be documented, not defined in method %s::%s().',
                $this->parameterName(),
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

    /**
     * @return string
     */
    public function parameterName()
    {
        $this->typeCheck->parameterName(func_get_args());

        return $this->parameterName;
    }

    private $className;
    private $functionName;
    private $parameterName;
    private $typeCheck;
}
