<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Resolver;

use Eloquent\Typhoon\Parameter\Parameter;
use Eloquent\Typhoon\Parameter\ParameterList;
use Eloquent\Typhoon\Parameter\Visitor;
use ReflectionMethod;
use Typhoon\Typhoon;

class ParameterListReflectionResolver implements Visitor
{
    /**
     * @param ReflectionMethod $reflector
     */
    public function __construct(ReflectionMethod $reflector)
    {
        $this->typhoon = Typhoon::get(__CLASS__, func_get_args());
        $this->reflector = $reflector;
    }

    /**
     * @return ReflectionMethod
     */
    public function reflector()
    {
        $this->typhoon->reflector(func_get_args());

        return $this->reflector;
    }

    /**
     * @param Parameter $parameter
     *
     * @return mixed
     */
    public function visitParameter(Parameter $parameter)
    {
        $this->typhoon->visitParameter(func_get_args());

        $reflector = $this->parameterReflectorByName(
            $parameter->name()
        );
        if (!$reflector) {
            return $parameter;
        }

        return new Parameter(
            $parameter->name(),
            $parameter->type(),
            $reflector->isOptional(),
            $parameter->description()
        );
    }

    /**
     * @param ParameterList $parameterList
     *
     * @return mixed
     */
    public function visitParameterList(ParameterList $parameterList)
    {
        $this->typhoon->visitParameterList(func_get_args());

        $parameters = array();
        foreach ($parameterList->parameters() as $parameter) {
            $parameters[] = $parameter->accept($this);
        }

        return new ParameterList(
            $parameters,
            $parameterList->isVariableLength()
        );
    }

    /**
     * @param string $name
     *
     * @return ReflectionParameter
     */
    protected function parameterReflectorByName($name)
    {
        $this->typhoon->parameterReflectorByName(func_get_args());

        foreach ($this->reflector()->getParameters() as $parameter) {
            if ($parameter->getName() === $name) {
                return $parameter;
            }
        }

        return null;
    }

    private $reflector;
    private $typhoon;
}
