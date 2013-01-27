<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Generator\ParameterListMerge\Exception;

use Eloquent\Typhoon\TypeCheck\TypeCheck;
use Exception;
use LogicException;

final class UndocumentedParameterException extends LogicException
{
    /**
     * @param string         $functionName
     * @param string         $parameterName
     * @param Exception|null $previous
     */
    public function __construct($functionName, $parameterName, Exception $previous = null)
    {
        $this->typeCheck = TypeCheck::get(__CLASS__, func_get_args());
        $this->functionName = $functionName;
        $this->parameterName = $parameterName;

        parent::__construct(
            sprintf(
                "Parameter '%s' is undocumented in '%s'.",
                $this->parameterName(),
                $this->functionName()
            ),
            0,
            $previous
        );
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

    private $functionName;
    private $parameterName;
    private $typeCheck;
}
