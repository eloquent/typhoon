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
use LogicException;
use Typhoon\Typhoon;

final class InvalidFunctionDocumentationException extends LogicException
{
    /**
     * @param string         $functionName
     * @param Exception|null $previous
     */
    public function __construct($functionName, Exception $previous = null)
    {
        $this->typhoon = Typhoon::get(__CLASS__, func_get_args());
        $this->functionName = $functionName;

        parent::__construct(
            sprintf(
                'Invalid param tags found in the documentation for %s().',
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

    private $functionName;
    private $typhoon;
}
