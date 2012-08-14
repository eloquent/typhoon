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

namespace Typhoon\Eloquent\Typhoon\Resolver;

use Typhoon\Exception\MissingArgumentException;
use Typhoon\Exception\UnexpectedArgumentException;
use Typhoon\Exception\UnexpectedArgumentValueException;

class ParameterListClassNameResolverTyphoon
{
    public function validateConstructor(array $arguments)
    {
        $argumentCount = count($arguments);
        if ($argumentCount < 1) {
            throw new MissingArgumentException('typeResolver', 0, 'Eloquent\\Typhax\\Resolver\\ObjectTypeClassNameResolver');
        } elseif ($argumentCount > 1) {
            throw new UnexpectedArgumentException(1, $arguments[1]);
        }

        $check = function($argument, $index) {
            $check = function($value) {
                return $value instanceof \Eloquent\Typhax\Resolver\ObjectTypeClassNameResolver;
            };
            if (!$check($argument)) {
                throw new UnexpectedArgumentValueException('typeResolver', $index, $argument, 'Eloquent\\Typhax\\Resolver\\ObjectTypeClassNameResolver');
            }
        };
        $check($arguments[0], 0);
    }

    public function typeResolver(array $arguments)
    {
        if (count($arguments) > 0) {
            throw new UnexpectedArgumentException(0, $arguments[0]);
        }
    }

    public function visitParameter(array $arguments)
    {
        $argumentCount = count($arguments);
        if ($argumentCount < 1) {
            throw new MissingArgumentException('parameter', 0, 'Eloquent\\Typhoon\\Parameter\\Parameter');
        } elseif ($argumentCount > 1) {
            throw new UnexpectedArgumentException(1, $arguments[1]);
        }

        $check = function($argument, $index) {
            $check = function($value) {
                return $value instanceof \Eloquent\Typhoon\Parameter\Parameter;
            };
            if (!$check($argument)) {
                throw new UnexpectedArgumentValueException('parameter', $index, $argument, 'Eloquent\\Typhoon\\Parameter\\Parameter');
            }
        };
        $check($arguments[0], 0);
    }

    public function visitParameterList(array $arguments)
    {
        $argumentCount = count($arguments);
        if ($argumentCount < 1) {
            throw new MissingArgumentException('parameterList', 0, 'Eloquent\\Typhoon\\Parameter\\ParameterList');
        } elseif ($argumentCount > 1) {
            throw new UnexpectedArgumentException(1, $arguments[1]);
        }

        $check = function($argument, $index) {
            $check = function($value) {
                return $value instanceof \Eloquent\Typhoon\Parameter\ParameterList;
            };
            if (!$check($argument)) {
                throw new UnexpectedArgumentValueException('parameterList', $index, $argument, 'Eloquent\\Typhoon\\Parameter\\ParameterList');
            }
        };
        $check($arguments[0], 0);
    }
}
