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

use Eloquent\Typhax\Resolver\ObjectTypeClassNameResolver;
use Eloquent\Typhoon\Parameter\Parameter;
use Eloquent\Typhoon\Parameter\ParameterList;
use Eloquent\Typhoon\Parameter\Visitor;

class ParameterListClassNameResolver implements Visitor
{
    /**
     * @param ObjectTypeClassNameResolver $typeResolver
     */
    public function __construct(ObjectTypeClassNameResolver $typeResolver)
    {
        $this->typeResolver = $typeResolver;
    }

    /**
     * @return ObjectTypeClassNameResolver
     */
    public function typeResolver()
    {
        return $this->typeResolver;
    }

    /**
     * @param Parameter $parameter
     *
     * @return mixed
     */
    public function visitParameter(Parameter $parameter)
    {
        return new Parameter(
            $parameter->name(),
            $parameter->type()->accept($this->typeResolver()),
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
        $parameters = array();
        foreach ($parameterList->parameters() as $parameter)
        {
            $parameters[] = $parameter->accept($this);
        }

        return new ParameterList(
            $parameters,
            $parameterList->isVariableLength()
        );
    }

    private $typeResolver;
}
