<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Parser\Exception;

use Eloquent\Cosmos\ClassName;
use Eloquent\Typhoon\TypeCheck\TypeCheck;
use Exception;
use LogicException;
use ReflectionParameter;

final class TypeHintUndefinedClassException extends LogicException
{
    /**
     * @param ClassName|null      $className
     * @param string              $functionName
     * @param ReflectionParameter $parameter
     * @param Exception|null      $previous
     */
    public function __construct(
        ClassName $className = null,
        $functionName,
        ReflectionParameter $parameter,
        Exception $previous = null
    ) {
        $this->typeCheck = TypeCheck::get(__CLASS__, func_get_args());
        $this->className = $className;
        $this->functionName = $functionName;
        $this->parameter = $parameter;

        $variableNamePattern = '[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*';
        $parameterPattern = sprintf(
            '/^Parameter #\d+ \[ <(?:optional|required)> (%s(?:\\\\%s)*)(?: or NULL)? \$%s(?: = NULL)? \]$/',
            $variableNamePattern,
            $variableNamePattern,
            $variableNamePattern
        );

        if (preg_match($parameterPattern, strval($parameter), $matches)) {
            $this->parameterClassName = ClassName::fromString($matches[1])->toAbsolute();
            $parameterClassNameString = $this->parameterClassName()->string();
        } else {
            $parameterClassNameString = 'unknown class';
        }

        if (null === $className) {
            $message = sprintf(
                'Unable to resolve type hint of %s for parameter $%s in function %s().',
                $parameterClassNameString,
                $parameter->getName(),
                $this->functionName()
            );
        } else {
            $message = sprintf(
                'Unable to resolve type hint of %s for parameter $%s in method %s::%s().',
                $parameterClassNameString,
                $parameter->getName(),
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
     * @return ReflectionParameter
     */
    public function parameter()
    {
        $this->typeCheck->parameter(func_get_args());

        return $this->parameter;
    }

    /**
     * @return ClassName|null
     */
    public function parameterClassName()
    {
        $this->typeCheck->parameterClassName(func_get_args());

        return $this->parameterClassName;
    }

    private $className;
    private $functionName;
    private $parameter;
    private $parameterClassName;
    private $typeCheck;
}
