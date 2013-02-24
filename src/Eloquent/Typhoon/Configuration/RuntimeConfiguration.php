<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Configuration;

use Eloquent\Cosmos\ClassName;
use Eloquent\Typhoon\TypeCheck\TypeCheck;

class RuntimeConfiguration
{
    /**
     * @param ClassName|null $validatorNamespace
     * @param boolean|null   $useNativeCallable
     */
    public function __construct(
        ClassName $validatorNamespace = null,
        $useNativeCallable = null
    ) {
        $this->typeCheck = TypeCheck::get(__CLASS__, func_get_args());
        if (null === $validatorNamespace) {
            $validatorNamespace = ClassName::fromAtoms(array('Typhoon'), true);
        }
        if (null === $useNativeCallable) {
            $useNativeCallable = false;
        }

        $this->validatorNamespace = $validatorNamespace;
        $this->useNativeCallable = $useNativeCallable;
    }

    /**
     * @param ClassName $validatorNamespace
     */
    public function setValidatorNamespace(ClassName $validatorNamespace)
    {
        $this->typeCheck->setValidatorNamespace(func_get_args());

        $this->validatorNamespace = $validatorNamespace->toAbsolute();
    }

    /**
     * @return ClassName
     */
    public function validatorNamespace()
    {
        $this->typeCheck->validatorNamespace(func_get_args());

        return $this->validatorNamespace;
    }

    /**
     * @param boolean $useNativeCallable
     */
    public function setUseNativeCallable($useNativeCallable)
    {
        $this->typeCheck->setUseNativeCallable(func_get_args());

        $this->useNativeCallable = $useNativeCallable;
    }

    /**
     * @return boolean
     */
    public function useNativeCallable()
    {
        $this->typeCheck->useNativeCallable(func_get_args());

        return $this->useNativeCallable;
    }

    private $validatorNamespace;
    private $useNativeCallable;
    private $typeCheck;
}
