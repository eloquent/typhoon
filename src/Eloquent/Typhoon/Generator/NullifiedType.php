<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Generator;

use Eloquent\Typhax\Type\MixedType;
use Eloquent\Typhax\Type\Type;
use Eloquent\Typhoon\TypeCheck\TypeCheck;

class NullifiedType extends MixedType
{
    /**
     * @param Type $originalType
     */
    public function __construct(Type $originalType)
    {
        $this->typeCheck = TypeCheck::get(__CLASS__, func_get_args());

        $this->originalType = $originalType;
    }

    /**
     * @return Type
     */
    public function originalType()
    {
        $this->typeCheck->originalType(func_get_args());

        return $this->originalType;
    }

    private $originalType;
    private $typeCheck;
}
