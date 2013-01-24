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

use Eloquent\Typhoon\TypeCheck\TypeCheck;

class ParameterList
{
    /**
     * @param array<Parameter> $parameters
     * @param boolean          $variableLength
     */
    public function __construct(
        array $parameters = array(),
        $variableLength = false
    ) {
        $this->typeCheck = TypeCheck::get(__CLASS__, func_get_args());
        $this->parameters = $parameters;
        $this->variableLength = $variableLength;
    }

    /**
     * @return array<Parameter>
     */
    public function parameters()
    {
        $this->typeCheck->parameters(func_get_args());

        return $this->parameters;
    }

    /**
     * @param string $name
     *
     * @return Parameter|null
     */
    public function parameterByName($name)
    {
        $this->typeCheck->parameterByName(func_get_args());

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
        $this->typeCheck->isVariableLength(func_get_args());

        return $this->variableLength;
    }

    /**
     * @return array<Parameter>
     */
    public function requiredParameters()
    {
        $this->typeCheck->requiredParameters(func_get_args());

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

    /**
     * @param Visitor $visitor
     *
     * @return mixed
     */
    public function accept(Visitor $visitor)
    {
        $this->typeCheck->accept(func_get_args());

        return $visitor->visitParameterList($this);
    }

    private $parameters;
    private $variableLength;
    private $typeCheck;
}
