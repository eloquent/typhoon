<?php
namespace Typhoon\Eloquent\Typhoon\Deployment;

class DeploymentManagerTyphoon
{
    public function validateConstructor(array $arguments)
    {
        $argumentCount = count($arguments);
        if ($argumentCount > 1) {
            throw new \InvalidArgumentException("Unexpected argument at index 2.");
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
                    throw new \InvalidArgumentException("Unexpected argument for parameter 'isolator' at index ".$index.".");
                }
            };
            $check($arguments[0], 0);
        }
    }

    public function deploy(array $arguments)
    {
        $argumentCount = count($arguments);
        if ($argumentCount < 1) {
            throw new \InvalidArgumentException("Missing argument for parameter 'path'.");
        } elseif ($argumentCount > 1) {
            throw new \InvalidArgumentException("Unexpected argument at index 2.");
        }

        $check = function($argument, $index) {
            $check = function($value) {
                return is_string($value);
            };
            if (!$check($argument)) {
                throw new \InvalidArgumentException("Unexpected argument for parameter 'path' at index ".$index.".");
            }
        };
        $check($arguments[0], 0);
    }

    public function copyFile(array $arguments)
    {
        $argumentCount = count($arguments);
        if ($argumentCount < 2) {
            if ($argumentCount < 1) {
                throw new \InvalidArgumentException("Missing argument for parameter 'from'.");
            }
            throw new \InvalidArgumentException("Missing argument for parameter 'to'.");
        } elseif ($argumentCount > 2) {
            throw new \InvalidArgumentException("Unexpected argument at index 3.");
        }

        $check = function($argument, $index) {
            $check = function($value) {
                return is_string($value);
            };
            if (!$check($argument)) {
                throw new \InvalidArgumentException("Unexpected argument for parameter 'from' at index ".$index.".");
            }
        };
        $check($arguments[0], 0);

        $check = function($argument, $index) {
            $check = function($value) {
                return is_string($value);
            };
            if (!$check($argument)) {
                throw new \InvalidArgumentException("Unexpected argument for parameter 'to' at index ".$index.".");
            }
        };
        $check($arguments[1], 1);
    }
}
