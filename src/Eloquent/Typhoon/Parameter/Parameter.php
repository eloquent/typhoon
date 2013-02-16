<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Parameter;

use Eloquent\Typhax\Type\Type;
use Eloquent\Typhoon\TypeCheck\TypeCheck;

class Parameter
{
    /**
     * @param string      $name
     * @param Type        $type
     * @param string|null $description
     * @param boolean     $optional
     * @param boolean     $byReference
     */
    public function __construct(
        $name,
        Type $type,
        $description = null,
        $optional = false,
        $byReference = false
    ) {
        $this->typeCheck = TypeCheck::get(__CLASS__, func_get_args());
        $this->name = $name;
        $this->type = $type;
        $this->description = $description;
        $this->optional = $optional;
        $this->byReference = $byReference;
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
     * @return Type
     */
    public function type()
    {
        $this->typeCheck->type(func_get_args());

        return $this->type;
    }

    /**
     * @return string|null
     */
    public function description()
    {
        $this->typeCheck->description(func_get_args());

        return $this->description;
    }

    /**
     * @return boolean
     */
    public function isOptional()
    {
        $this->typeCheck->isOptional(func_get_args());

        return $this->optional;
    }

    /**
     * @return boolean
     */
    public function isByReference()
    {
        $this->typeCheck->isByReference(func_get_args());

        return $this->byReference;
    }

    /**
     * @param Visitor $visitor
     *
     * @return mixed
     */
    public function accept(Visitor $visitor)
    {
        $this->typeCheck->accept(func_get_args());

        return $visitor->visitParameter($this);
    }

    private $name;
    private $type;
    private $description;
    private $optional;
    private $byReference;
    private $typeCheck;
}
