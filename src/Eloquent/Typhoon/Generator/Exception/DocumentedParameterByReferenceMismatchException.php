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

final class DocumentedParameterByReferenceMismatchException extends LogicException
{
    /**
     * @param string         $functionName
     * @param string         $parameterName
     * @param boolean        $documentedIsByReference
     * @param boolean        $nativeIsByReference
     * @param Exception|null $previous
     */
    public function __construct(
        $functionName,
        $parameterName,
        $documentedIsByReference,
        $nativeIsByReference,
        Exception $previous = null
    ) {
        $this->typhoon = Typhoon::get(__CLASS__, func_get_args());
        $this->functionName = $functionName;
        $this->parameterName = $parameterName;
        $this->documentedIsByReference = $documentedIsByReference;
        $this->nativeIsByReference = $nativeIsByReference;

        if ($documentedIsByReference) {
            $documentedVariableType = 'by-reference';
            $nativeVariableType = 'by-value';
        } else {
            $documentedVariableType = 'by-value';
            $nativeVariableType = 'by-reference';
        }

        parent::__construct(
            sprintf(
                "Parameter '%s' is documented as %s but defined as %s in '%s'.",
                $this->parameterName(),
                $documentedVariableType,
                $nativeVariableType,
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

    /**
     * @return boolean
     */
    public function documentedIsByReference()
    {
        $this->typhoon->documentedIsByReference(func_get_args());

        return $this->documentedIsByReference;
    }

    /**
     * @return boolean
     */
    public function nativeIsByReference()
    {
        $this->typhoon->nativeIsByReference(func_get_args());

        return $this->nativeIsByReference;
    }

    private $functionName;
    private $parameterName;
    private $documentedIsByReference;
    private $nativeIsByReference;
    private $typhoon;
}
