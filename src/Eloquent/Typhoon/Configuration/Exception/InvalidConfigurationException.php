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

final class InvalidConfigurationException extends Exception
{
    /**
     * @param string         $reason
     * @param Exception|null $previous
     */
    public function __construct(
        $reason,
        Exception $previous = null
    ) {
        $this->typeCheck = TypeCheck::get(__CLASS__, func_get_args());
        $this->reason = $reason;

        parent::__construct(
            sprintf(
                "Invalid configuration. %s",
                $this->reason()
            ),
            0,
            $previous
        );
    }

    /**
     * @return string
     */
    public function reason()
    {
        $this->typeCheck->reason(func_get_args());

        return $this->reason;
    }

    private $reason;
    private $typeCheck;
}
