<?php
namespace Typhoon\Eloquent\Typhoon\Parameter;

class ParameterListTyphoon
{
    public function createUnrestricted(array $arguments)
    {
        if (count($arguments) > 0) {
            throw new \InvalidArgumentException("Unexpected argument at index 1.");
        }
    }

    public function validateConstructor(array $arguments)
    {
        $argumentCount = count($arguments);
        if ($argumentCount > 2) {
            throw new \InvalidArgumentException("Unexpected argument at index 3.");
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
                    throw new \InvalidArgumentException("Unexpected argument for parameter 'parameters' at index ".$index.".");
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
                    throw new \InvalidArgumentException("Unexpected argument for parameter 'variableLength' at index ".$index.".");
                }
            };
            $check($arguments[1], 1);
        }
    }

    public function parameters(array $arguments)
    {
        if (count($arguments) > 0) {
            throw new \InvalidArgumentException("Unexpected argument at index 1.");
        }
    }

    public function parameterByName(array $arguments)
    {
        if (count($arguments) > 0) {
            throw new \InvalidArgumentException("Unexpected argument at index 1.");
        }
    }

    public function isVariableLength(array $arguments)
    {
        if (count($arguments) > 0) {
            throw new \InvalidArgumentException("Unexpected argument at index 1.");
        }
    }

    public function requiredParameters(array $arguments)
    {
        if (count($arguments) > 0) {
            throw new \InvalidArgumentException("Unexpected argument at index 1.");
        }
    }
}
