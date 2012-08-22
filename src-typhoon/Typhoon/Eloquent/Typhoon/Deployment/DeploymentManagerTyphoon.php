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

namespace Typhoon\Eloquent\Typhoon\Deployment;

use Typhoon\Exception\MissingArgumentException;
use Typhoon\Exception\UnexpectedArgumentException;
use Typhoon\Exception\UnexpectedArgumentValueException;
use Typhoon\Validator;

class DeploymentManagerTyphoon extends Validator
{
    public function validateConstructor(array $arguments)
    {
        $argumentCount = count($arguments);
        if ($argumentCount > 1) {
            throw new UnexpectedArgumentException(1, $arguments[1]);
        }

        if ($argumentCount > 0) {
            $check = function($argument, $index) {
                $check = function($value) {
                    $check = function($value) {
                        return $value instanceof \Icecave\Isolator\Isolator;
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
                    throw new UnexpectedArgumentValueException('isolator', $index, $argument, 'Icecave\\Isolator\\Isolator|null');
                }
            };
            $check($arguments[0], 0);
        }
    }

    public function deploy(array $arguments)
    {
        $argumentCount = count($arguments);
        if ($argumentCount < 1) {
            throw new MissingArgumentException('path', 0, 'string');
        } elseif ($argumentCount > 1) {
            throw new UnexpectedArgumentException(1, $arguments[1]);
        }

        $check = function($argument, $index) {
            $check = function($value) {
                return is_string($value);
            };
            if (!$check($argument)) {
                throw new UnexpectedArgumentValueException('path', $index, $argument, 'string');
            }
        };
        $check($arguments[0], 0);
    }

    public function copyFile(array $arguments)
    {
        $argumentCount = count($arguments);
        if ($argumentCount < 2) {
            if ($argumentCount < 1) {
                throw new MissingArgumentException('from', 0, 'string');
            }
            throw new MissingArgumentException('to', 1, 'string');
        } elseif ($argumentCount > 2) {
            throw new UnexpectedArgumentException(2, $arguments[2]);
        }

        $check = function($argument, $index) {
            $check = function($value) {
                return is_string($value);
            };
            if (!$check($argument)) {
                throw new UnexpectedArgumentValueException('from', $index, $argument, 'string');
            }
        };
        $check($arguments[0], 0);

        $check = function($argument, $index) {
            $check = function($value) {
                return is_string($value);
            };
            if (!$check($argument)) {
                throw new UnexpectedArgumentValueException('to', $index, $argument, 'string');
            }
        };
        $check($arguments[1], 1);
    }
}
