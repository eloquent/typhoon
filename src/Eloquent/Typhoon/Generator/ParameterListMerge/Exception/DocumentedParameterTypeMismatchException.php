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
use Eloquent\Typhax\Renderer\TypeRenderer;
use Eloquent\Typhax\Type\Type;
use Eloquent\Typhoon\TypeCheck\TypeCheck;
use Exception;
use LogicException;

final class DocumentedParameterTypeMismatchException extends LogicException
{
    /**
     * @param ClassName|null    $className
     * @param string            $functionName
     * @param string            $parameterName
     * @param Type              $documentedType
     * @param Type              $nativeType
     * @param Exception|null    $previous
     * @param TypeRenderer|null $typeRenderer
     */
    public function __construct(
        ClassName $className = null,
        $functionName,
        $parameterName,
        Type $documentedType,
        Type $nativeType,
        Exception $previous = null,
        TypeRenderer $typeRenderer = null
    ) {
        $this->typeCheck = TypeCheck::get(__CLASS__, func_get_args());
        if (null === $typeRenderer) {
            $typeRenderer = new TypeRenderer;
        }

        $this->className = $className;
        $this->functionName = $functionName;
        $this->parameterName = $parameterName;
        $this->documentedType = $documentedType;
        $this->nativeType = $nativeType;
        $this->typeRenderer = $typeRenderer;

        if (null === $className) {
            $message = sprintf(
                "Documented type '%s' is not correct for defined type '%s' for parameter $%s in function %s().",
                $this->documentedType()->accept($this->typeRenderer()),
                $this->nativeType()->accept($this->typeRenderer()),
                $this->parameterName(),
                $this->functionName()
            );
        } else {
            $message = sprintf(
                "Documented type '%s' is not correct for defined type '%s' for parameter $%s in method %s::%s().",
                $this->documentedType()->accept($this->typeRenderer()),
                $this->nativeType()->accept($this->typeRenderer()),
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

    /**
     * @return Type
     */
    public function documentedType()
    {
        $this->typeCheck->documentedType(func_get_args());

        return $this->documentedType;
    }

    /**
     * @return Type
     */
    public function nativeType()
    {
        $this->typeCheck->nativeType(func_get_args());

        return $this->nativeType;
    }

    /**
     * @return TypeRenderer
     */
    public function typeRenderer()
    {
        $this->typeCheck->typeRenderer(func_get_args());

        return $this->typeRenderer;
    }

    private $className;
    private $functionName;
    private $parameterName;
    private $documentedType;
    private $nativeType;
    private $typeRenderer;
    private $typeCheck;
}
