<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

// @codeCoverageIgnoreStart

namespace Eloquent\Typhoon\Parameter;

use Icecave\Visita\IVisitor;

interface Visitor extends IVisitor
{
    /**
     * @param Parameter $parameter
     *
     * @return mixed
     */
    public function visitParameter(Parameter $parameter);

    /**
     * @param ParameterList $parameterList
     *
     * @return mixed
     */
    public function visitParameterList(ParameterList $parameterList);
}