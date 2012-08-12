<?php
namespace Typhoon\Eloquent\Typhoon\Parameter;

class ParameterTyphoon
{
    public function __construct(array $arguments)
    {
        $argumentCount = count($arguments);
        if ($argumentCount < 2) {
            if ($argumentCount < 1) {
                throw new \InvalidArgumentException("Missing argument for parameter 'name'.");
            }
            throw new \InvalidArgumentException("Missing argument for parameter 'type'.");
        } elseif ($argumentCount > 4) {
            throw new \InvalidArgumentException("Unexpected argument at index 5.");
        }

        $check = function($argument, $index) {
            $check = function($value) {
                return is_string($value);
            };
            if (!$check($argument)) {
                throw new \InvalidArgumentException("Unexpected argument for parameter 'name' at index ".$index.".");
            }
        };
        $check($arguments[0], 0);

        $check = function($argument, $index) {
            $check = function($value) {
                return $value instanceof \Eloquent\Typhax\Type\Type;
            };
            if (!$check($argument)) {
                throw new \InvalidArgumentException("Unexpected argument for parameter 'type' at index ".$index.".");
            }
        };
        $check($arguments[1], 1);

        if ($argumentCount > 2) {
            $check = function($argument, $index) {
                $check = function($value) {
                    return is_bool($value);
                };
                if (!$check($argument)) {
                    throw new \InvalidArgumentException("Unexpected argument for parameter 'optional' at index ".$index.".");
                }
            };
            $check($arguments[2], 2);
        }

        if ($argumentCount > 3) {
            $check = function($argument, $index) {
                $check = function($value) {
                    $check = function($value) {
                        return is_string($value);
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
                    throw new \InvalidArgumentException("Unexpected argument for parameter 'description' at index ".$index.".");
                }
            };
            $check($arguments[3], 3);
        }
    }

    public function name(array $arguments)
    {
        if (count($arguments) > 0) {
            throw new \InvalidArgumentException("Unexpected argument at index 1.");
        }
    }

    public function type(array $arguments)
    {
        if (count($arguments) > 0) {
            throw new \InvalidArgumentException("Unexpected argument at index 1.");
        }
    }

    public function isOptional(array $arguments)
    {
        if (count($arguments) > 0) {
            throw new \InvalidArgumentException("Unexpected argument at index 1.");
        }
    }

    public function description(array $arguments)
    {
        if (count($arguments) > 0) {
            throw new \InvalidArgumentException("Unexpected argument at index 1.");
        }
    }
}
