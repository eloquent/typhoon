<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\ClassMapper;

use Eloquent\Typhoon\TypeCheck\TypeCheck;
use ReflectionProperty;

class PropertyDefinition extends ClassMemberDefinition
{
    /**
     * @return ReflectionProperty
     */
    public function createReflector()
    {
        TypeCheck::get(__CLASS__)->createReflector(func_get_args());

        return new ReflectionProperty($this->className()->string(), $this->name());
    }
}
