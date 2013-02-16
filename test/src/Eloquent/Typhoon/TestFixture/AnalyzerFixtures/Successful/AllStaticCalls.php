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

use Typhoon\TypeCheck;

class AllStaticCalls
{
    public function foo()
    {
        TypeCheck::get(__CLASS__)->foo(func_get_args());
    }

    public function __bar()
    {
        TypeCheck::get(__CLASS__)->validateBar(func_get_args());
    }
}
