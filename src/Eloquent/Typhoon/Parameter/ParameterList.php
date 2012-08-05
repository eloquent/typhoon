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

class ParameterList
{
    /**
     * @param array<Parameter> $parameters
     * @param boolean $variableLength
     */
    public function __construct(array $parameters = array(), $variableLength = false)
    {
        $this->parameters = $parameters;
        $this->variableLength = $variableLength;
    }

    /**
     * @return array<Parameter>
     */
    public function parameters()
    {
        return $this->parameters;
    }

    /**
     * @return Parameter|null
     */
    public function parameterByName($name)
    {
        foreach ($this->parameters() as $parameter) {
            if ($parameter->name() === $name) {
                return $parameter;
            }
        }

        return null;
    }

    /**
     * @return boolean
     */
    public function isVariableLength()
    {
        return $this->variableLength;
    }

    private $parameters;
    private $variableLength;
}
