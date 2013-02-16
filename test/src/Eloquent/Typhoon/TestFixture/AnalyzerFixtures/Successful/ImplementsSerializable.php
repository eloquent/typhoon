<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\TestFixture\AnalyzerFixtures\Successful;

use Serializable;
use Typhoon\TypeCheck;

class ImplementsSerializable implements Serializable
{
    public function __construct()
    {
        $this->typeCheck = TypeCheck::get(__CLASS__, func_get_args());
    }

    public function serialize()
    {
        $this->typeCheck->serialize(func_get_args());
    }

    public function unserialize($serialized)
    {
    }

    private $typeCheck;
}
