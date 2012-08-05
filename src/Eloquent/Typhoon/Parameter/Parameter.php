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

class Parameter extends Host
{
    /**
     * @param string $name
     * @param Type $type
     * @param boolean $optional
     * @param string|null $description
     */
    public function __construct($name, Type $type, $optional = false, $description = null)
    {
        $this->name = $name;
        $this->type = $type;
        $this->optional = $optional;
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @return Type
     */
    public function type()
    {
        return $this->type;
    }

    /**
     * @return boolean
     */
    public function isOptional()
    {
        return $this->optional;
    }

    /**
     * @return string|null
     */
    public function description()
    {
        return $this->description;
    }

    private $name;
    private $type;
    private $optional;
    private $description;
}
