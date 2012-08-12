<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon;

abstract class Typhoon
{
    /**
     * @param string $className
     * @param array<integer,mixed> $arguments
     *
     * @return object
     */
    public static function get($className, array $arguments = null)
    {
        if (!array_key_exists($className, static::$instances)) {
            $validatorClassName = 'Typhoon\\'.$className.'Typhoon';
            static::install($className, new $validatorClassName);
        }

        $validator = static::$instances[$className];
        if (null !== $arguments) {
            $validator->validateConstructor($arguments);
        }

        return $validator;
    }

    /**
     * @param string $className
     * @param object $validator
     */
    public static function install($className, $validator)
    {
        static::$instances[$className] = $validator;
    }

    private static $instances = array();
}
