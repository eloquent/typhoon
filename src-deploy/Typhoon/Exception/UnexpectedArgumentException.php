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

final class UnexpectedArgumentException extends UnexpectedInputException
{
    /**
     * @param integer $index
     * @param mixed $value
     * @param Exception|null $previous
     */
    public function __construct($index, $value, Exception $previous = null)
    {
        $this->index = $index;
        $this->value = $value;

        $this->unexpectedType = gettype($this->value);

        parent::__construct(
            sprintf(
                "Unexpected argument of type '%s' at index %d.",
                $this->unexpectedType,
                $this->index
            ),
            $previous
        );
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
    public function unexpectedType()
    {
        return $this->unexpectedType;
    }

    private $index;
    private $value;
    private $unexpectedType;
}
