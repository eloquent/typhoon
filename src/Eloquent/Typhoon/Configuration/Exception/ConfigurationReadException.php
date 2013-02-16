<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Configuration\Exception;

use Eloquent\Typhoon\TypeCheck\TypeCheck;
use Exception;
use RuntimeException;

final class ConfigurationReadException extends RuntimeException
{
    /**
     * @param string         $path
     * @param Exception|null $previous
     */
    public function __construct(
        $path,
        Exception $previous = null
    ) {
        $this->typeCheck = TypeCheck::get(__CLASS__, func_get_args());
        $this->path = $path;

        parent::__construct(
            sprintf(
                "Unable to read configuration from '%s'.",
                $this->path()
            ),
            0,
            $previous
        );
    }

    /**
     * @return string
     */
    public function path()
    {
        $this->typeCheck->path(func_get_args());

        return $this->path;
    }

    private $path;
    private $typeCheck;
}
