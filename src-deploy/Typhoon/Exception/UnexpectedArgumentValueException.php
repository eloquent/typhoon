<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Typhoon\Exception;

use Exception;

final class UnexpectedArgumentValueException extends UnexpectedInputException
{
    /**
     * @param string $parameterName
     * @param integer $index
     * @param string $value
     * @param string $expectedType
     * @param Exception|null $previous
     */
    public function __construct(
        $parameterName,
        $index,
        $value,
        $expectedType,
        Exception $previous = null
    ) {
        $this->parameterName = $parameterName;
        $this->index = $index;
        $this->value = $value;
        $this->expectedType = $expectedType;

        $this->unexpectedType = gettype($this->value);

        parent::__construct(
            sprintf(
                "Unexpected argument of type '%s' for parameter '%s' at index %d. Expected '%s'.",
                $this->unexpectedType,
                $this->parameterName,
                $this->index,
                $this->expectedType
            ),
            $previous
        );
    }

    /**
     * @return string
     */
    public function parameterName()
    {
        return $this->parameterName;
    }

    /**
     * @return integer
     */
    public function index()
    {
        return $this->index;
    }

    /**
     * @return string
     */
    public function value()
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function expectedType()
    {
        return $this->expectedType;
    }

    /**
     * @return string
     */
    public function unexpectedType()
    {
        return $this->unexpectedType;
    }

    private $parameterName;
    private $index;
    private $value;
    private $expectedType;
    private $unexpectedType;
}
