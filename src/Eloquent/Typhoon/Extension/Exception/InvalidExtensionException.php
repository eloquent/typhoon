<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Extension\Exception;

use Eloquent\Typhoon\TypeCheck\TypeCheck;
use Exception;
use LogicException;

final class InvalidExtensionException extends LogicException
{
    /**
     * @param string         $className
     * @param Exception|null $previous
     */
    public function __construct(
        $className,
        Exception $previous = null
    ) {
        $this->typeCheck = TypeCheck::get(__CLASS__, func_get_args());
        $this->className = $className;

        parent::__construct(
            sprintf(
                "The extension type '%s' does not exist, or does not implement ExtensionInterface.",
                $this->className()
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
        $this->typeCheck->className(func_get_args());

        return $this->className;
    }

    private $className;
    private $typeCheck;
}
