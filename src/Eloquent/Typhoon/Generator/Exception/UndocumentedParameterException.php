<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Generator\Exception;

use Exception;
use LogicException;
use Typhoon\Typhoon;

final class UndocumentedParameterException extends LogicException
{
    /**
     * @param string         $functionName
     * @param string         $parameterName
     * @param Exception|null $previous
     */
    public function __construct($functionName, $parameterName, Exception $previous = null)
    {
        $this->typhoon = Typhoon::get(__CLASS__, func_get_args());
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
        $this->typhoon->functionName(func_get_args());

        return $this->functionName;
    }

    /**
     * @return string
     */
    public function parameterName()
    {
        $this->typhoon->parameterName(func_get_args());

        return $this->parameterName;
    }

    private $functionName;
    private $parameterName;
    private $typhoon;
}
