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

namespace Typhoon\Eloquent\Typhoon\Generator\Exception;

use Typhoon\Exception\MissingArgumentException;
use Typhoon\Exception\UnexpectedArgumentException;
use Typhoon\Exception\UnexpectedArgumentValueException;

class DocumentedParameterNameMismatchExceptionTyphoon
{
    public function validateConstructor(array $arguments)
    {
        $argumentCount = count($arguments);
        if ($argumentCount < 3) {
            if ($argumentCount < 1) {
                throw new MissingArgumentException('functionName', 0, 'string');
            }
            if ($argumentCount < 2) {
                throw new MissingArgumentException('documentedParameterName', 1, 'string');
            }
            throw new MissingArgumentException('nativeParameterName', 2, 'string');
        } elseif ($argumentCount > 4) {
            throw new UnexpectedArgumentException(4, $arguments[4]);
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
                return is_string($value);
            };
            if (!$check($argument)) {
                throw new UnexpectedArgumentValueException('documentedParameterName', $index, $argument, 'string');
            }
        };
        $check($arguments[1], 1);

        $check = function($argument, $index) {
            $check = function($value) {
                return is_string($value);
            };
            if (!$check($argument)) {
                throw new UnexpectedArgumentValueException('nativeParameterName', $index, $argument, 'string');
            }
        };
        $check($arguments[2], 2);

        if ($argumentCount > 3) {
            $check = function($argument, $index) {
                $check = function($value) {
                    $check = function($value) {
                        return $value instanceof \Exception;
                    };
                    if ($check($value)) {
                        return true;
                    }

                    $check = function($value) {
                        return $value === null;
                    };
                    if ($check($value)) {
                        return true;
                    }

                    return false;
                };
                if (!$check($argument)) {
                    throw new UnexpectedArgumentValueException('previous', $index, $argument, 'Exception|null');
                }
            };
            $check($arguments[3], 3);
        }
    }

    public function functionName(array $arguments)
    {
        if (count($arguments) > 0) {
            throw new UnexpectedArgumentException(0, $arguments[0]);
        }
    }

    public function documentedParameterName(array $arguments)
    {
        if (count($arguments) > 0) {
            throw new UnexpectedArgumentException(0, $arguments[0]);
        }
    }

    public function nativeParameterName(array $arguments)
    {
        if (count($arguments) > 0) {
            throw new UnexpectedArgumentException(0, $arguments[0]);
        }
    }
}