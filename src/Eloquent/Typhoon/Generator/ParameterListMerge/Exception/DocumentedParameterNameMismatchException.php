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

final class DocumentedParameterNameMismatchException extends LogicException
{
    /**
     * @param ClassName|null $className
     * @param string         $functionName
     * @param string         $documentedParameterName
     * @param string         $nativeParameterName
     * @param Exception|null $previous
     */
    public function __construct(
        ClassName $className = null,
        $functionName,
        $documentedParameterName,
        $nativeParameterName,
        Exception $previous = null
    ) {
        $this->typeCheck = TypeCheck::get(__CLASS__, func_get_args());
        $this->className = $className;
        $this->functionName = $functionName;
        $this->documentedParameterName = $documentedParameterName;
        $this->nativeParameterName = $nativeParameterName;

        if (null === $className) {
            $message = sprintf(
                'Documented parameter name $%s does not match defined parameter name $%s in function %s().',
                $this->documentedParameterName(),
                $this->nativeParameterName(),
                $this->functionName()
            );
        } else {
            $message = sprintf(
                'Documented parameter name $%s does not match defined parameter name $%s in method %s::%s().',
                $this->documentedParameterName(),
                $this->nativeParameterName(),
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
    public function documentedParameterName()
    {
        $this->typeCheck->documentedParameterName(func_get_args());

        return $this->documentedParameterName;
    }

    /**
     * @return string
     */
    public function nativeParameterName()
    {
        $this->typeCheck->nativeParameterName(func_get_args());

        return $this->nativeParameterName;
    }

    private $className;
    private $functionName;
    private $documentedParameterName;
    private $nativeParameterName;
    private $typeCheck;
}
