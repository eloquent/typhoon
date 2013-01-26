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

interface ExtensionLoaderInterface
{
    /**
     * @param string  $className   The name of the extension class.
     * @param boolean $forceReload
     *
     * @return ExtensionInterface
     */
    public function load($className, $forceReload = false);

    /**
     * @param string $className The name of the extension class.
     */
    public function unload($className);

    /**
     * @param string $className The name of the extension class.
     *
     * @return boolean
     */
    public function isLoaded($className);
}
