<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\ClassMapper\Exception;

use Eloquent\Cosmos\ClassName;
use Eloquent\Typhoon\TypeCheck\TypeCheck;
use Exception;
use LogicException;

final class UndefinedMethodException extends LogicException
{
    /**
     * @param ClassName      $className
     * @param string         $methodName
     * @param Exception|null $previous
     */
    public function __construct(ClassName $className, $methodName, Exception $previous = null)
    {
        $this->typeCheck = TypeCheck::get(__CLASS__, func_get_args());
        $this->className = $className;
        $this->methodName = $methodName;

        parent::__construct(
            sprintf(
                "Undefined method '%s::%s()'.",
                $className->string(),
                $methodName
            ),
            0,
            $previous
        );
    }

    /**
     * @return ClassName
     */
    public function className()
    {
        $this->typeCheck->className(func_get_args());

        return $this->className;
    }

    /**
     * @return string
     */
    public function methodName()
    {
        $this->typeCheck->methodName(func_get_args());

        return $this->methodName;
    }

    private $className;
    private $methodName;
    private $typeCheck;
}
