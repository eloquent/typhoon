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

use Typhoon\TypeCheck;

abstract class StandardSetup
{
    public static function bar()
    {
        TypeCheck::get(__CLASS__)->bar(func_get_args());
    }

    public function __construct()
    {
        $this->typeCheck = TypeCheck::get(__CLASS__, func_get_args());
    }

    public function foo()
    {
        $this->typeCheck->foo(func_get_args());
    }

    public function __baz()
    {
        $this->typeCheck->validateBaz(func_get_args());
    }

    abstract public function qux();

    private $typeCheck;
}
