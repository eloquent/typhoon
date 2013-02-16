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

use Eloquent\Typhoon\TypeCheck\TypeCheck;
use Exception;
use LogicException;

abstract class ParseException extends LogicException
{
    /**
     * @param string         $message
     * @param integer        $position
     * @param Exception|null $previous
     */
    public function __construct($message, $position, Exception $previous = null)
    {
        $this->typeCheck = TypeCheck::get(__CLASS__, func_get_args());
        $this->position = $position;

        parent::__construct($message, 0, $previous);
    }

    /**
     * @return integer
     */
    public function position()
    {
        $this->typeCheck->position(func_get_args());

        return $this->position;
    }

    private $position;
    private $typeCheck;
}
