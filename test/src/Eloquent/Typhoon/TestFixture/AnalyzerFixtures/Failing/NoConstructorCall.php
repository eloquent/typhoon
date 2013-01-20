<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\TestFixture\AnalyzerFixtures\Failing;

class NoConstructorCall
{
    public function foo()
    {
        $this->typeCheck->foo(func_get_args());
    }
}
