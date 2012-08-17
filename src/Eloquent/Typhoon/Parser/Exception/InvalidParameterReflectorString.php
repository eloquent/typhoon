<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Parser\Exception;

use Exception;
use Typhoon\Typhoon;

final class InvalidParameterReflectorString extends ParseException
{
    /**
     * @param string $parameterString
     * @param Exception|null $previous
     */
    public function __construct($parameterString, Exception $previous = null)
    {
        $this->typhoon = Typhoon::get(__CLASS__, func_get_args());
        $this->parameterString = $parameterString;

        parent::__construct(
            sprintf(
                "Unable to parse ReflectionParameter string '%'.",
                $this->parameterString()
            ),
            $position,
            $previous
        );
    }

    /**
     * @return string
     */
    public function parameterString()
    {
        $this->typhoon->parameterString(func_get_args());

        return $this->parameterString;
    }

    private $parameterString;
    private $typhoon;
}
