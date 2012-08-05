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
     * @param Type $type
     * @param string|null $name
     * @param string|null $description
     */
    public function __construct(Type $type, $name = null, $description = null)
    {
        $this->type = $type;
        $this->name = $name;
        $this->description = $description;
    }

    /**
     * @return Type
     */
    public function type()
    {
        return $this->type;
    }

    /**
     * @return string|null
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function description()
    {
        return $this->description;
    }

    private $type;
    private $name;
    private $description;
}
