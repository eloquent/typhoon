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

use Eloquent\Typhax\Renderer\TypeRenderer;
use Eloquent\Typhax\Type\Type;
use Exception;
use LogicException;
use Typhoon\Typhoon;

final class DocumentedParameterTypeMismatchException extends LogicException
{
    /**
     * @param string            $functionName
     * @param string            $parameterName
     * @param Type              $documentedType
     * @param Type              $nativeType
     * @param Exception|null    $previous
     * @param TypeRenderer|null $typeRenderer
     */
    public function __construct(
        $functionName,
        $parameterName,
        Type $documentedType,
        Type $nativeType,
        Exception $previous = null,
        TypeRenderer $typeRenderer = null
    ) {
        $this->typhoon = Typhoon::get(__CLASS__, func_get_args());
        if (null === $typeRenderer) {
            $typeRenderer = new TypeRenderer;
        }

        $this->functionName = $functionName;
        $this->parameterName = $parameterName;
        $this->documentedType = $documentedType;
        $this->nativeType = $nativeType;
        $this->typeRenderer = $typeRenderer;

        parent::__construct(
            sprintf(
                "Documented type '%s' is not correct for defined type '%s' for parameter '%s' in '%s'.",
                $this->documentedType()->accept($this->typeRenderer()),
                $this->nativeType()->accept($this->typeRenderer()),
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

    /**
     * @return Type
     */
    public function documentedType()
    {
        $this->typhoon->documentedType(func_get_args());

        return $this->documentedType;
    }

    /**
     * @return Type
     */
    public function nativeType()
    {
        $this->typhoon->nativeType(func_get_args());

        return $this->nativeType;
    }

    /**
     * @return TypeRenderer
     */
    public function typeRenderer()
    {
        $this->typhoon->typeRenderer(func_get_args());

        return $this->typeRenderer;
    }

    private $functionName;
    private $parameterName;
    private $documentedType;
    private $nativeType;
    private $typeRenderer;
    private $typhoon;
}
