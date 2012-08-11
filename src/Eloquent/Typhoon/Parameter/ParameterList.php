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

use Eloquent\Typhax\Type\MixedType;
use Icecave\Visita\Host;

class ParameterList extends Host
{
    /**
     * @return ParameterList
     */
    public static function createUnrestricted()
    {
        return new ParameterList(
            array(
                new Parameter(
                    'undefined',
                    new MixedType,
                    true
                ),
            ),
            true
        );
    }

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

    /**
     * @return array<Parameter>
     */
    public function requiredParameters()
    {
        $requiredParameters = array();
        $parameters = $this->parameters();
        $numberOfParameters = count($parameters);
        $requiredEncountered = false;

        for ($i = $numberOfParameters - 1; $i >= 0; $i --) {
            $requiredEncountered =
                $requiredEncountered ||
                !$parameters[$i]->isOptional()
            ;

            if ($requiredEncountered) {
                $requiredParameters[] = $parameters[$i];
            }
        }

        return array_reverse($requiredParameters);
    }

    private $parameters;
    private $variableLength;
}
