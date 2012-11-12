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
    /**
     * @param boolean|null $useNativeCallable
     * @param boolean|null $runtimeGeneration
     */
    public function __construct(
        $useNativeCallable = null,
        $runtimeGeneration = null
    ) {
        $this->typhoon = Typhoon::get(__CLASS__, func_get_args());
        if (null === $useNativeCallable) {
            $useNativeCallable = true;
        }
        if (null === $runtimeGeneration) {
            $runtimeGeneration = false;
        }

        $this->useNativeCallable = $useNativeCallable;
        $this->runtimeGeneration = $runtimeGeneration;
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

    /**
     * @param boolean $runtimeGeneration
     */
    public function setRuntimeGeneration($runtimeGeneration)
    {
        $this->typhoon->setRuntimeGeneration(func_get_args());

        $this->runtimeGeneration = $runtimeGeneration;
    }

    /**
     * @return boolean
     */
    public function runtimeGeneration()
    {
        $this->typhoon->runtimeGeneration(func_get_args());

        return $this->runtimeGeneration;
    }

    private $useNativeCallable;
    private $runtimeGeneration;
    private $typhoon;
}
