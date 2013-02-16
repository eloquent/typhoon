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

final class DocumentedParameterByReferenceMismatchException extends LogicException
{
    /**
     * @param ClassName|null $className
     * @param string         $functionName
     * @param string         $parameterName
     * @param boolean        $documentedIsByReference
     * @param boolean        $nativeIsByReference
     * @param Exception|null $previous
     */
    public function __construct(
        ClassName $className = null,
        $functionName,
        $parameterName,
        $documentedIsByReference,
        $nativeIsByReference,
        Exception $previous = null
    ) {
        $this->typeCheck = TypeCheck::get(__CLASS__, func_get_args());
        $this->className = $className;
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

        if (null === $className) {
            $message = sprintf(
                'Parameter $%s is documented as %s but defined as %s in function %s().',
                $this->parameterName(),
                $documentedVariableType,
                $nativeVariableType,
                $this->functionName()
            );
        } else {
            $message = sprintf(
                'Parameter $%s is documented as %s but defined as %s in method %s::%s().',
                $this->parameterName(),
                $documentedVariableType,
                $nativeVariableType,
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

    /**
     * @return boolean
     */
    public function documentedIsByReference()
    {
        $this->typeCheck->documentedIsByReference(func_get_args());

        return $this->documentedIsByReference;
    }

    /**
     * @return boolean
     */
    public function nativeIsByReference()
    {
        $this->typeCheck->nativeIsByReference(func_get_args());

        return $this->nativeIsByReference;
    }

    private $className;
    private $functionName;
    private $parameterName;
    private $documentedIsByReference;
    private $nativeIsByReference;
    private $typeCheck;
}
