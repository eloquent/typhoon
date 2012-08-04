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

class Parameter
{
    /**
     * @param Type $type
     * @param string $name
     * @param string $description
     */
    public function __construct(Type $type, $name, $description = null)
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
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function description()
    {
        return $this->description;
    }

    private $type;
    private $name;
    private $description;
}
