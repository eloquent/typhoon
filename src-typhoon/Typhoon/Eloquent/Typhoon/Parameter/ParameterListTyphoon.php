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

namespace Typhoon\Eloquent\Typhoon\Parameter;

use Typhoon\Exception\MissingArgumentException;
use Typhoon\Exception\UnexpectedArgumentException;
use Typhoon\Exception\UnexpectedArgumentValueException;
use Typhoon\Validator;

class ParameterListTyphoon extends Validator
{
    public function validateConstruct(array $arguments)
    {
        $argumentCount = count($arguments);
        if ($argumentCount > 2) {
            throw new UnexpectedArgumentException(2, $arguments[2]);
        }

        if ($argumentCount > 0) {
            $check = function($argument, $index) {
                $check = function($value) {
                    $primaryCheck = function($value) {
                        return is_array($value);
                    };
                    if (!$primaryCheck($value)) {
                        return false;
                    }

                    $keyCheck = function($value) {
                        return true;
                    };
                    $valueCheck = function($value) {
                        return $value instanceof \Eloquent\Typhoon\Parameter\Parameter;
                    };
                    foreach ($value as $key => $subValue) {
                        if (!$keyCheck($key)) {
                            return false;
                        }
                        if (!$valueCheck($subValue)) {
                            return false;
                        }
                    }

                    return true;
                };
                if (!$check($argument)) {
                    throw new UnexpectedArgumentValueException('parameters', $index, $argument, 'array<Eloquent\\Typhoon\\Parameter\\Parameter>');
                }
            };
            $check($arguments[0], 0);
        }

        if ($argumentCount > 1) {
            $check = function($argument, $index) {
                $check = function($value) {
                    return is_bool($value);
                };
                if (!$check($argument)) {
                    throw new UnexpectedArgumentValueException('variableLength', $index, $argument, 'boolean');
                }
            };
            $check($arguments[1], 1);
        }
    }

    public function parameters(array $arguments)
    {
        if (count($arguments) > 0) {
            throw new UnexpectedArgumentException(0, $arguments[0]);
        }
    }

    public function parameterByName(array $arguments)
    {
        $argumentCount = count($arguments);
        if ($argumentCount < 1) {
            throw new MissingArgumentException('name', 0, 'string');
        } elseif ($argumentCount > 1) {
            throw new UnexpectedArgumentException(1, $arguments[1]);
        }

        $check = function($argument, $index) {
            $check = function($value) {
                return is_string($value);
            };
            if (!$check($argument)) {
                throw new UnexpectedArgumentValueException('name', $index, $argument, 'string');
            }
        };
        $check($arguments[0], 0);
    }

    public function isVariableLength(array $arguments)
    {
        if (count($arguments) > 0) {
            throw new UnexpectedArgumentException(0, $arguments[0]);
        }
    }

    public function requiredParameters(array $arguments)
    {
        if (count($arguments) > 0) {
            throw new UnexpectedArgumentException(0, $arguments[0]);
        }
    }
}
