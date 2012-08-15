<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Parameter;

use Eloquent\Typhax\Type\Type;
use Icecave\Visita\Host;
use Typhoon\Typhoon;

class Parameter extends Host
{
    /**
     * @param string $name
     * @param Type $type
     * @param string|null $description
     * @param boolean $optional
     * @param boolean $byReference
     */
    public function __construct(
        $name,
        Type $type,
        $description = null,
        $optional = false,
        $byReference = false
    ) {
        $this->typhoon = Typhoon::get(__CLASS__, func_get_args());
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
        $this->typhoon->name(func_get_args());

        return $this->name;
    }

    /**
     * @return Type
     */
    public function type()
    {
        $this->typhoon->type(func_get_args());

        return $this->type;
    }

    /**
     * @return string|null
     */
    public function description()
    {
        $this->typhoon->description(func_get_args());

        return $this->description;
    }

    /**
     * @return boolean
     */
    public function isOptional()
    {
        $this->typhoon->isOptional(func_get_args());

        return $this->optional;
    }

    /**
     * @return boolean
     */
    public function isByReference()
    {
        $this->typhoon->isByReference(func_get_args());

        return $this->byReference;
    }

    private $name;
    private $type;
    private $description;
    private $optional;
    private $byReference;
    private $typhoon;
}
