<?php

/*
 * This file was generated by [Typhoon](https://github.com/eloquent/typhoon).
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the
 * [LICENSE](https://raw.github.com/eloquent/typhoon/master/LICENSE)
 * file that is distributed with Typhoon.
 */

namespace Typhoon\Eloquent\Typhoon\TestFixture;

class ExampleTyphoon
{
    public function validateConstructor(array $arguments)
    {
        $this->arguments = $arguments;
    }

    public function arguments()
    {
        return $this->arguments;
    }

    private $arguments;
}
