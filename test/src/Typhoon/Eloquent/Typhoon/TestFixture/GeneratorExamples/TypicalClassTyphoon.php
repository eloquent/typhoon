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

namespace Typhoon\Eloquent\Typhoon\TestFixture\GeneratorExamples;

use Typhoon\Exception\MissingArgumentException;
use Typhoon\Exception\UnexpectedArgumentException;
use Typhoon\Exception\UnexpectedArgumentValueException;

class TypicalClassTyphoon
{
    public function validateConstructor(array $arguments)
    {
        $argumentCount = count($arguments);
        if ($argumentCount < 2) {
            if ($argumentCount < 1) {
                throw new MissingArgumentException('foo', 0, 'string');
            }
            throw new MissingArgumentException('bar', 1, 'integer');
        } elseif ($argumentCount > 2) {
            throw new UnexpectedArgumentException(2, $arguments[2]);
        }

        $check = function($argument, $index) {
            $check = function($value) {
                return is_string($value);
            };
            if (!$check($argument)) {
                throw new UnexpectedArgumentValueException('foo', $index, $argument, 'string');
            }
        };
        $check($arguments[0], 0);

        $check = function($argument, $index) {
            $check = function($value) {
                return is_integer($value);
            };
            if (!$check($argument)) {
                throw new UnexpectedArgumentValueException('bar', $index, $argument, 'integer');
            }
        };
        $check($arguments[1], 1);
    }

    public function typicalMethod(array $arguments)
    {
        $argumentCount = count($arguments);
        if ($argumentCount < 1) {
            throw new MissingArgumentException('foo', 0, 'float');
        }

        $check = function($argument, $index) {
            $check = function($value) {
                return is_float($value);
            };
            if (!$check($argument)) {
                throw new UnexpectedArgumentValueException('foo', $index, $argument, 'float');
            }
        };
        $check($arguments[0], 0);

        if ($argumentCount > 1) {
            $check = function($argument, $index) {
                $check = function($value) {
                    $check = function($value) {
                        return true;
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
                    throw new UnexpectedArgumentValueException('bar', $index, $argument, 'mixed|null');
                }
            };
            $check($arguments[1], 1);
        }

        if ($argumentCount > 2) {
            $check = function($argument, $index) {
                $check = function($value) {
                    if (
                        !is_resource($value) ||
                        'stream' !== get_resource_type($value)
                    ) {
                        return false;
                    }

                    $streamMetaData = stream_get_meta_data($value);

                    if (
                        false === strpos($streamMetaData['mode'], 'w') &&
                        false === strpos($streamMetaData['mode'], 'a') &&
                        false === strpos($streamMetaData['mode'], 'x') &&
                        false === strpos($streamMetaData['mode'], 'c') &&
                        false === strpos($streamMetaData['mode'], '+')
                    ) {
                        return false;
                    }

                    return true;
                };
                if (!$check($argument)) {
                    throw new UnexpectedArgumentValueException('baz', $index, $argument, 'stream {writable: true}');
                }
            };
            for ($i = 2; $i < $argumentCount; $i ++) {
                $check($arguments[$i], $i);
            }
        }
    }

    public function undocumentedMethod(array $arguments)
    {
        $argumentCount = count($arguments);

        if ($argumentCount > 0) {
            $check = function($argument, $index) {
                $check = function($value) {
                    return true;
                };
                if (!$check($argument)) {
                    throw new UnexpectedArgumentValueException('undefined', $index, $argument, 'mixed');
                }
            };
            for ($i = 0; $i < $argumentCount; $i ++) {
                $check($arguments[$i], $i);
            }
        }
    }
}
