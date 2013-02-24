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

/**
 * @see https://github.com/eloquent/typhoon/issues/106
 */
class Issue106
{
    public function __construct()
    {
        parent::__construct();

        $this->typeCheck = TypeCheck::get(__CLASS__, func_get_args());
    }

    public function foo()
    {
        $this->typeCheck->foo(func_get_args());
    }

    private $typeCheck;
}
