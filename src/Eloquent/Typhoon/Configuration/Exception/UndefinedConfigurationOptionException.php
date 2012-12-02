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

use Eloquent\Typhoon\Validators\Typhoon;
use Exception;

final class UndefinedConfigurationOptionException extends Exception
{
    /**
     * @param string         $optionName
     * @param Exception|null $previous
     */
    public function __construct(
        $optionName,
        Exception $previous = null
    ) {
        $this->typhoon = Typhoon::get(__CLASS__, func_get_args());
        $this->optionName = $optionName;

        parent::__construct(
            sprintf(
                "Undefined configuration option '%s'.",
                $this->optionName()
            ),
            0,
            $previous
        );
    }

    /**
     * @return string
     */
    public function optionName()
    {
        $this->typhoon->optionName(func_get_args());

        return $this->optionName;
    }

    private $optionName;
    private $typhoon;
}
