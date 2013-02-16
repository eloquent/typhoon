<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\TestFixture\AnalyzerFixtures\Failing;

use Typhoon\TypeCheck;

class MissingCalls
{
    public static function baz()
    {
    }

    public function __construct()
    {
        $this->typeCheck = TypeCheck::get(__CLASS__, func_get_args());
    }

    public function foo()
    {
    }

    public function bar()
    {
    }

    private $typeCheck;
}
