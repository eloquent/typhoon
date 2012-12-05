<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Configuration\Exception;

use Eloquent\Typhoon\TypeCheck\TypeCheck;
use Exception;
use RuntimeException;

final class InvalidJSONException extends RuntimeException
{
    /**
     * @param integer        $jsonErrorCode
     * @param string         $path
     * @param Exception|null $previous
     */
    public function __construct(
        $jsonErrorCode,
        $path,
        Exception $previous = null
    ) {
        $this->typeCheck = TypeCheck::get(__CLASS__, func_get_args());
        $this->jsonErrorCode = $jsonErrorCode;
        $this->path = $path;

        switch ($jsonErrorCode) {
            case JSON_ERROR_DEPTH:
                $this->jsonErrorMessage = 'The maximum stack depth has been exceeded.';
                break;
            case JSON_ERROR_STATE_MISMATCH:
                $this->jsonErrorMessage = 'Invalid or malformed JSON.';
                break;
            case JSON_ERROR_CTRL_CHAR:
                $this->jsonErrorMessage = 'Control character error, possibly incorrectly encoded.';
                break;
            case JSON_ERROR_SYNTAX:
                $this->jsonErrorMessage = 'Syntax error.';
                break;
            case JSON_ERROR_UTF8:
                $this->jsonErrorMessage = 'Malformed UTF-8 characters, possibly incorrectly encoded.';
                break;
            default:
                $this->jsonErrorMessage = 'An unknown error occurred.';
        }

        parent::__construct(
            sprintf(
                "Invalid JSON in '%s'. %s",
                $this->path(),
                $this->jsonErrorMessage()
            ),
            0,
            $previous
        );
    }

    /**
     * @return integer
     */
    public function jsonErrorCode()
    {
        $this->typeCheck->jsonErrorCode(func_get_args());

        return $this->jsonErrorCode;
    }

    /**
     * @return integer
     */
    public function jsonErrorMessage()
    {
        $this->typeCheck->jsonErrorMessage(func_get_args());

        return $this->jsonErrorMessage;
    }

    /**
     * @return string
     */
    public function path()
    {
        $this->typeCheck->path(func_get_args());

        return $this->path;
    }

    private $jsonErrorCode;
    private $jsonErrorMessage;
    private $path;
    private $typeCheck;
}
