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

abstract class ClassMemberDefinition
{
    /**
     * @param string         $name
     * @param boolean        $isStatic
     * @param AccessModifier $accessModifier
     * @param integer        $lineNumber
     * @param string         $source
     */
    public function __construct(
        $name,
        $isStatic,
        AccessModifier $accessModifier,
        $lineNumber,
        $source
    ) {
        $this->typeCheck = TypeCheck::get(__CLASS__, func_get_args());
        $this->name = $name;
        $this->isStatic = $isStatic;
        $this->accessModifier = $accessModifier;
        $this->lineNumber = $lineNumber;
        $this->source = $source;
    }

    /**
     * @return string
     */
    public function name()
    {
        $this->typeCheck->name(func_get_args());

        return $this->name;
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

    /**
     * @return string
     */
    public function source()
    {
        $this->typeCheck->source(func_get_args());

        return $this->source;
    }

    /**
     * @return integer
     */
    public function endLineNumber()
    {
        $this->typeCheck->endLineNumber(func_get_args());

        return
            $this->lineNumber() +
            preg_match_all('/\r\n|\r|\n/', $this->source(), $matches)
        ;
    }

    private $name;
    private $isStatic;
    private $accessModifier;
    private $lineNumber;
    private $typeCheck;
}
