<?php
namespace Typhoon\Eloquent\Typhoon\TestFixture\GeneratorExamples;

class TypicalClassTyphoon
{
    public function validateConstructor(array $arguments)
    {
        $argumentCount = count($arguments);
        if ($argumentCount < 2) {
            if ($argumentCount < 1) {
                throw new \InvalidArgumentException("Missing argument for parameter 'foo'.");
            }
            throw new \InvalidArgumentException("Missing argument for parameter 'bar'.");
        } elseif ($argumentCount > 2) {
            throw new \InvalidArgumentException("Unexpected argument at index 3.");
        }

        $check = function($argument, $index) {
            $check = function($value) {
                return is_string($value);
            };
            if (!$check($argument)) {
                throw new \InvalidArgumentException("Unexpected argument for parameter 'foo' at index ".$index.".");
            }
        };
        $check($arguments[0], 0);

        $check = function($argument, $index) {
            $check = function($value) {
                return is_integer($value);
            };
            if (!$check($argument)) {
                throw new \InvalidArgumentException("Unexpected argument for parameter 'bar' at index ".$index.".");
            }
        };
        $check($arguments[1], 1);
    }

    public function typicalMethod(array $arguments)
    {
        $argumentCount = count($arguments);
        if ($argumentCount < 1) {
            throw new \InvalidArgumentException("Missing argument for parameter 'foo'.");
        }

        $check = function($argument, $index) {
            $check = function($value) {
                return is_float($value);
            };
            if (!$check($argument)) {
                throw new \InvalidArgumentException("Unexpected argument for parameter 'foo' at index ".$index.".");
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
                    throw new \InvalidArgumentException("Unexpected argument for parameter 'bar' at index ".$index.".");
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
                    throw new \InvalidArgumentException("Unexpected argument for parameter 'baz' at index ".$index.".");
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
                    throw new \InvalidArgumentException("Unexpected argument for parameter 'undefined' at index ".$index.".");
                }
            };
            for ($i = 0; $i < $argumentCount; $i ++) {
                $check($arguments[$i], $i);
            }
        }
    }
}
