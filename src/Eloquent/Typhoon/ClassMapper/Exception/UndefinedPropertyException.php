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

use Eloquent\Cosmos\ClassName;
use Eloquent\Typhoon\TypeCheck\TypeCheck;
use Exception;
use LogicException;

final class UndefinedPropertyException extends LogicException
{
    /**
     * @param ClassName      $className
     * @param string         $propertyName
     * @param Exception|null $previous
     */
    public function __construct(ClassName $className, $propertyName, Exception $previous = null)
    {
        $this->typeCheck = TypeCheck::get(__CLASS__, func_get_args());
        $this->className = $className;
        $this->propertyName = $propertyName;

        parent::__construct(
            sprintf(
                "Undefined property '%s::$%s'.",
                $className->string(),
                $propertyName
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
    public function propertyName()
    {
        $this->typeCheck->propertyName(func_get_args());

        return $this->propertyName;
    }

    private $className;
    private $propertyName;
    private $typeCheck;
}
