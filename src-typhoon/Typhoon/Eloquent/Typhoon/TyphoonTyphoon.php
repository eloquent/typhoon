<?php
namespace Typhoon\Eloquent\Typhoon;

class TyphoonTyphoon
{
    public function get(array $arguments)
    {
        $argumentCount = count($arguments);
        if ($argumentCount < 1) {
            throw new \InvalidArgumentException("Missing argument for parameter 'className'.");
        } elseif ($argumentCount > 2) {
            throw new \InvalidArgumentException("Unexpected argument at index 3.");
        }

        $check = function($argument, $index) {
            $check = function($value) {
                return is_string($value);
            };
            if (!$check($argument)) {
                throw new \InvalidArgumentException("Unexpected argument for parameter 'className' at index ".$index.".");
            }
        };
        $check($arguments[0], 0);

        if ($argumentCount > 1) {
            $check = function($argument, $index) {
                $check = function($value) {
                    $primaryCheck = function($value) {
                        return is_array($value);
                    };
                    if (!$primaryCheck($value)) {
                        return false;
                    }

                    $keyCheck = function($value) {
                        return is_integer($value);
                    };
                    $valueCheck = function($value) {
                        return true;
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
                    throw new \InvalidArgumentException("Unexpected argument for parameter 'arguments' at index ".$index.".");
                }
            };
            $check($arguments[1], 1);
        }
    }

    public function install(array $arguments)
    {
        $argumentCount = count($arguments);
        if ($argumentCount < 2) {
            if ($argumentCount < 1) {
                throw new \InvalidArgumentException("Missing argument for parameter 'className'.");
            }
            throw new \InvalidArgumentException("Missing argument for parameter 'validator'.");
        } elseif ($argumentCount > 2) {
            throw new \InvalidArgumentException("Unexpected argument at index 3.");
        }

        $check = function($argument, $index) {
            $check = function($value) {
                return is_string($value);
            };
            if (!$check($argument)) {
                throw new \InvalidArgumentException("Unexpected argument for parameter 'className' at index ".$index.".");
            }
        };
        $check($arguments[0], 0);

        $check = function($argument, $index) {
            $check = function($value) {
                return is_object($value);
            };
            if (!$check($argument)) {
                throw new \InvalidArgumentException("Unexpected argument for parameter 'validator' at index ".$index.".");
            }
        };
        $check($arguments[1], 1);
    }
}
