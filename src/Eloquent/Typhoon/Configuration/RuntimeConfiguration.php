<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Configuration;

use Typhoon\Typhoon;

class RuntimeConfiguration
{
    public function __construct()
    {
        $this->typhoon = Typhoon::get(__CLASS__, func_get_args());
        $this->useNativeCallable = true;
    }

    /**
     * @param boolean $useNativeCallable
     */
    public function setUseNativeCallable($useNativeCallable)
    {
        $this->typhoon->setUseNativeCallable(func_get_args());

        $this->useNativeCallable = $useNativeCallable;
    }

    /**
     * @return boolean
     */
    public function useNativeCallable()
    {
        $this->typhoon->useNativeCallable(func_get_args());

        return $this->useNativeCallable;
    }

    private $useNativeCallable;
    private $typhoon;
}
