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
use Icecave\Pasta\AST\Type\AccessModifier;

class PropertyDefinition
{
    /**
     * @param string         $propertyName
     * @param boolean        $isStatic
     * @param AccessModifier $accessModifier
     * @param integer        $lineNumber
     */
    public function __construct(
        $propertyName,
        $isStatic,
        AccessModifier $accessModifier,
        $lineNumber
    ) {
        $this->typeCheck = TypeCheck::get(__CLASS__, func_get_args());
        $this->propertyName = $propertyName;
        $this->isStatic = $isStatic;
        $this->accessModifier = $accessModifier;
        $this->lineNumber = $lineNumber;
    }

    /**
     * @return string
     */
    public function propertyName()
    {
        $this->typeCheck->propertyName(func_get_args());

        return $this->propertyName;
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
     * @return AccessModifier
     */
    public function accessModifier()
    {
        $this->typeCheck->accessModifier(func_get_args());

        return $this->accessModifier;
    }

    /**
     * @return integer
     */
    public function lineNumber()
    {
        $this->typeCheck->lineNumber(func_get_args());

        return $this->lineNumber;
    }

    private $propertyName;
    private $isStatic;
    private $lineNumber;
    private $typeCheck;
}
