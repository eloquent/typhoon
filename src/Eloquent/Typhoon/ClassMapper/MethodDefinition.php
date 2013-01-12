<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\ClassMapper;

use Eloquent\Typhoon\TypeCheck\TypeCheck;

class MethodDefinition
{
    /**
     * @param string  $methodName
     * @param boolean $isStatic
     * @param integer $lineNumber
     * @param string  $source
     */
    public function __construct(
        $methodName,
        $isStatic,
        $lineNumber,
        $source
    ) {
        $this->typeCheck = TypeCheck::get(__CLASS__, func_get_args());
        $this->methodName = $methodName;
        $this->isStatic = $isStatic;
        $this->lineNumber = $lineNumber;
        $this->source = $source;
    }

    /**
     * @return string
     */
    public function methodName()
    {
        $this->typeCheck->methodName(func_get_args());

        return $this->methodName;
    }

    /**
     * @return boolean
     */
    public function isStatic()
    {
        $this->typeCheck->isStatic(func_get_args());

        return $this->isStatic;
    }

    /**
     * @return integer
     */
    public function lineNumber()
    {
        $this->typeCheck->lineNumber(func_get_args());

        return $this->lineNumber;
    }

    /**
     * @return string
     */
    public function source()
    {
        $this->typeCheck->source(func_get_args());

        return $this->source;
    }

    private $methodName;
    private $isStatic;
    private $lineNumber;
    private $source;
    private $typeCheck;
}
