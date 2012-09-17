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

use Eloquent\Enumeration\Enumeration;
use Exception as NativeException;

final class ConfigurationOption extends Enumeration
{
    const LOADER_PATHS = 'loaderPaths';
    const OUTPUT_PATH = 'outputPath';
    const SOURCE_PATHS = 'sourcePaths';
    const USE_NATIVE_CALLABLE = 'useNativeCallable';

    /**
     * @param string $className
     * @param string $property
     * @param mixed $value
     * @param NativeException|null $previous
     *
     * @return Exception\UndefinedConfigurationOptionException
     */
    protected static function createUndefinedInstanceException(
        $className,
        $property,
        $value,
        NativeException $previous = null
    ) {
        return new Exception\UndefinedConfigurationOptionException($value, $previous);
    }
}
