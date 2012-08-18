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

final class DocumentedParameterNameMismatchException extends LogicException
{
    /**
     * @param string $functionName
     * @param string $documentedParameterName
     * @param string $nativeParameterName
     * @param Exception|null $previous
     */
    public function __construct(
        $functionName,
        $documentedParameterName,
        $nativeParameterName,
        Exception $previous = null
    ) {
        $this->typhoon = Typhoon::get(__CLASS__, func_get_args());
        $this->functionName = $functionName;
        $this->documentedParameterName = $documentedParameterName;
        $this->nativeParameterName = $nativeParameterName;

        parent::__construct(
            sprintf(
                "Documented parameter name '%s' does not match defined parameter name '%s' in '%s'.",
                $this->documentedParameterName(),
                $this->nativeParameterName(),
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
    public function documentedParameterName()
    {
        $this->typhoon->documentedParameterName(func_get_args());

        return $this->documentedParameterName;
    }

    /**
     * @return string
     */
    public function nativeParameterName()
    {
        $this->typhoon->nativeParameterName(func_get_args());

        return $this->nativeParameterName;
    }

    private $functionName;
    private $documentedParameterName;
    private $nativeParameterName;
    private $typhoon;
}
