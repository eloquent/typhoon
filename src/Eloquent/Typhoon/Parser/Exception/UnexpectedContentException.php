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

final class UnexpectedContentException extends ParseException
{
    /**
     * @param string $expected
     * @param integer $position
     * @param Exception|null $previous
     */
    public function __construct($expected, $position, Exception $previous = null)
    {
        $this->expected = $expected;

        $message =
            "Unexpected content at position ".
            $position.
            ". Expected '".
            $expected.
            "'."
        ;

        parent::__construct($message, $position, $previous);
    }

    /**
     * @return string
     */
    public function expected()
    {
        return $this->expected;
    }

    private $expected;
}
