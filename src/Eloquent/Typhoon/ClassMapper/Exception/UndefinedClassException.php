<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\ClassMapper\Exception;

use Exception;
use LogicException;
use Typhoon\Typhoon;

final class UndefinedClassException extends LogicException
{
    /**
     * @param string $className
     * @param Exception|null $previous
     */
    public function __construct($className, Exception $previous = null)
    {
        $this->typhoon = Typhoon::get(__CLASS__, func_get_args());
        $this->className = $className;

        parent::__construct(
            sprintf(
                "Undefined class '%'.",
                $this->className
            ),
            0,
            $previous
        );
    }

    /**
     * @return string
     */
    public function className()
    {
        $this->typhoon->className(func_get_args());

        return $this->className;
    }

    private $className;
}
