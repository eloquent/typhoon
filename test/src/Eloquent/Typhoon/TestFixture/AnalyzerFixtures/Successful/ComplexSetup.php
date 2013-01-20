<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\TestFixture\AnalyzerFixtures\Successful;

use Typhoon\TypeCheck as Baz;

class ComplexSetup
{
    public function __construct(
        array $array = array('one', 'two', array('three'), static::FOUR),
        SomeClass $class = null
    ) {
        $this->bar = Baz::get(__CLASS__, func_get_args());
    }

    public function foo()
    {
        $this->typeCheck->foo(func_get_args());
    }

    private $bar;
}
