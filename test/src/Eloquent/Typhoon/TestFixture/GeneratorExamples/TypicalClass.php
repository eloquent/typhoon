<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\TestFixture\GeneratorExamples;

class TypicalClass
{
    /**
     * @param string $foo
     * @param integer $bar
     */
    public function __construct($foo, $bar)
    {
        $this->typhoon = Typhoon::get(__CLASS__, func_get_args());
    }

    /**
     * @param float $foo
     * @param Object|null $bar
     * @param stream {writable: true} &$baz,...
     */
    protected function typicalMethod($foo, Object $bar = null)
    {
    }

    private function undocumentedMethod()
    {
    }

    private $typhoon;
}
