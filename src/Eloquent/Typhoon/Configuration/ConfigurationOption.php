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

use Eloquent\Enumeration\Enumeration;
use Eloquent\Typhoon\TypeCheck\TypeCheck;
use Exception as NativeException;

final class ConfigurationOption extends Enumeration
{
    const LOADER_PATHS = 'loader-paths';
    const OUTPUT_PATH = 'output-path';
    const SOURCE_PATHS = 'source-paths';
    const USE_NATIVE_CALLABLE = 'use-native-callable';
    const VALIDATOR_NAMESPACE = 'validator-namespace';

    /**
     * @param string               $className
     * @param string               $property
     * @param mixed                $value
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
        TypeCheck::get(__CLASS__)->createUndefinedInstanceException(func_get_args());

        return new Exception\UndefinedConfigurationOptionException($value, $previous);
    }
}
