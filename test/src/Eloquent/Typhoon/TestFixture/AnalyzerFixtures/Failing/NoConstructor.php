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

class NoConstructor
{
    public function foo()
    {
        $this->typeCheck->foo(func_get_args());
    }

    private $typeCheck;
}
