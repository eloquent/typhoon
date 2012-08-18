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

namespace Typhoon\Eloquent\Typhoon\Generator;

use Typhoon\Exception\MissingArgumentException;
use Typhoon\Exception\UnexpectedArgumentException;
use Typhoon\Exception\UnexpectedArgumentValueException;

class NativeParameterListMergeToolTyphoon
{
    public function validateConstructor(array $arguments)
    {
        if (count($arguments) > 0) {
            throw new UnexpectedArgumentException(0, $arguments[0]);
        }
    }

    public function merge(array $arguments)
    {
        $argumentCount = count($arguments);
        if ($argumentCount < 3) {
            if ($argumentCount < 1) {
                throw new MissingArgumentException('functionName', 0, 'string');
            }
            if ($argumentCount < 2) {
                throw new MissingArgumentException('documentedParameterList', 1, 'Eloquent\\Typhoon\\Parameter\\ParameterList');
            }
            throw new MissingArgumentException('nativeParameterList', 2, 'Eloquent\\Typhoon\\Parameter\\ParameterList');
        } elseif ($argumentCount > 3) {
            throw new UnexpectedArgumentException(3, $arguments[3]);
        }

        $check = function($argument, $index) {
            $check = function($value) {
                return is_string($value);
            };
            if (!$check($argument)) {
                throw new UnexpectedArgumentValueException('functionName', $index, $argument, 'string');
            }
        };
        $check($arguments[0], 0);

        $check = function($argument, $index) {
            $check = function($value) {
                return $value instanceof \Eloquent\Typhoon\Parameter\ParameterList;
            };
            if (!$check($argument)) {
                throw new UnexpectedArgumentValueException('documentedParameterList', $index, $argument, 'Eloquent\\Typhoon\\Parameter\\ParameterList');
            }
        };
        $check($arguments[1], 1);

        $check = function($argument, $index) {
            $check = function($value) {
                return $value instanceof \Eloquent\Typhoon\Parameter\ParameterList;
            };
            if (!$check($argument)) {
                throw new UnexpectedArgumentValueException('nativeParameterList', $index, $argument, 'Eloquent\\Typhoon\\Parameter\\ParameterList');
            }
        };
        $check($arguments[2], 2);
    }

    public function mergeParameter(array $arguments)
    {
        $argumentCount = count($arguments);
        if ($argumentCount < 3) {
            if ($argumentCount < 1) {
                throw new MissingArgumentException('functionName', 0, 'string');
            }
            if ($argumentCount < 2) {
                throw new MissingArgumentException('documentedParameter', 1, 'Eloquent\\Typhoon\\Parameter\\Parameter');
            }
            throw new MissingArgumentException('nativeParameter', 2, 'Eloquent\\Typhoon\\Parameter\\Parameter');
        } elseif ($argumentCount > 3) {
            throw new UnexpectedArgumentException(3, $arguments[3]);
        }

        $check = function($argument, $index) {
            $check = function($value) {
                return is_string($value);
            };
            if (!$check($argument)) {
                throw new UnexpectedArgumentValueException('functionName', $index, $argument, 'string');
            }
        };
        $check($arguments[0], 0);

        $check = function($argument, $index) {
            $check = function($value) {
                return $value instanceof \Eloquent\Typhoon\Parameter\Parameter;
            };
            if (!$check($argument)) {
                throw new UnexpectedArgumentValueException('documentedParameter', $index, $argument, 'Eloquent\\Typhoon\\Parameter\\Parameter');
            }
        };
        $check($arguments[1], 1);

        $check = function($argument, $index) {
            $check = function($value) {
                return $value instanceof \Eloquent\Typhoon\Parameter\Parameter;
            };
            if (!$check($argument)) {
                throw new UnexpectedArgumentValueException('nativeParameter', $index, $argument, 'Eloquent\\Typhoon\\Parameter\\Parameter');
            }
        };
        $check($arguments[2], 2);
    }
}
