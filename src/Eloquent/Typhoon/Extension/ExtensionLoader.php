<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Extension;

use Eloquent\Typhoon\TypeCheck\TypeCheck;
use ReflectionClass;
use ReflectionException;

class ExtensionLoader implements ExtensionLoaderInterface
{
    /**
     * @param array $constructorArguments Arguments to forward to each extension's constructor.
     */
    public function __construct(array $constructorArguments = array())
    {
        $this->typeCheck = TypeCheck::get(__CLASS__, func_get_args());

        $this->constructorArguments = $constructorArguments;
        $this->extensions = array();
    }

    /**
     * @param string $className The name of the extension class.
     * @param boolean $forceReload
     *
     * @return ExtensionInterface
     */
    public function load($className, $forceReload = false)
    {
        $this->typeCheck->load(func_get_args());

        // Extension is already loaded ...
        if (!$forceReload && $this->isLoaded($className)) {
            return $this->extensions[$className];
        }

        // Obtain a reflector ...
        try {
            $reflector = new ReflectionClass($className);
        } catch (ReflectionException $e) {
            throw new Exception\InvalidExtensionException($className, $e);
        }

        // Verify interface ...
        if (!$reflector->implementsInterface('Eloquent\Typhoon\Extension\ExtensionInterface')) {
            throw new Exception\InvalidExtensionException($className);
        }

        return $this->extensions[$className] = $reflector->newInstanceArgs($this->constructorArguments);
    }

    /**
     * @param string $className The name of the extension class.
     */
    public function unload($className)
    {
        $this->typeCheck->unload(func_get_args());

        unset($this->extensions[$className]);
    }

    /**
     * @param string $className The name of the extension class.
     *
     * @return boolean
     */
    public function isLoaded($className)
    {
        $this->typeCheck->isLoaded(func_get_args());

        return array_key_exists($className, $this->extensions);
    }

    private $constructorArguments;
    private $extensions;
    private $typeCheck;
}
