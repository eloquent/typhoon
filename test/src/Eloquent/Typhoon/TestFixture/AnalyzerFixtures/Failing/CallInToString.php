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

use Typhoon\TypeCheck;

class CallInToString
{
    public function __toString()
    {
        TypeCheck::get(__CLASS__)->validateToString(func_get_args());
    }
}
