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

class NoNonStaticMethods
{
    public static function foo()
    {
        TypeCheck::get(__CLASS__)->foo(func_get_args());
    }
}
