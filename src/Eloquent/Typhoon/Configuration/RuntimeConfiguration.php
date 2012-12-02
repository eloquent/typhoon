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

use Eloquent\Typhoon\Validators\Typhoon;

class RuntimeConfiguration
{
    /**
     * @param string|null  $validatorNamespace
     * @param boolean|null $useNativeCallable
     */
    public function __construct(
        $validatorNamespace = null,
        $useNativeCallable = null
    ) {
        $this->typhoon = Typhoon::get(__CLASS__, func_get_args());
        if (null === $validatorNamespace) {
            $validatorNamespace = 'Typhoon';
        }
        if (null === $useNativeCallable) {
            $useNativeCallable = true;
        }

        $this->validatorNamespace = $validatorNamespace;
        $this->useNativeCallable = $useNativeCallable;
    }

    /**
     * @param string $validatorNamespace
     */
    public function setValidatorNamespace($validatorNamespace)
    {
        $this->typhoon->setValidatorNamespace(func_get_args());

        $this->validatorNamespace = $validatorNamespace;
    }

    /**
     * @return string
     */
    public function validatorNamespace()
    {
        $this->typhoon->validatorNamespace(func_get_args());

        return $this->validatorNamespace;
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

    private $validatorNamespace;
    private $useNativeCallable;
    private $typhoon;
}
