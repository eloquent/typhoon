<?php
namespace Typhoon\Eloquent\Typhoon\Parser\Exception;

class ParseExceptionTyphoon
{
    public function __construct(array $arguments)
    {
        $argumentCount = count($arguments);
        if ($argumentCount < 2) {
            if ($argumentCount < 1) {
                throw new \InvalidArgumentException("Missing argument for parameter 'message'.");
            }
            throw new \InvalidArgumentException("Missing argument for parameter 'position'.");
        } elseif ($argumentCount > 3) {
            throw new \InvalidArgumentException("Unexpected argument at index 4.");
        }

        $check = function($argument, $index) {
            $check = function($value) {
                return is_string($value);
            };
            if (!$check($argument)) {
                throw new \InvalidArgumentException("Unexpected argument for parameter 'message' at index ".$index.".");
            }
        };
        $check($arguments[0], 0);

        $check = function($argument, $index) {
            $check = function($value) {
                return is_integer($value);
            };
            if (!$check($argument)) {
                throw new \InvalidArgumentException("Unexpected argument for parameter 'position' at index ".$index.".");
            }
        };
        $check($arguments[1], 1);

        if ($argumentCount > 2) {
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
                    throw new \InvalidArgumentException("Unexpected argument for parameter 'previous' at index ".$index.".");
                }
            };
            $check($arguments[2], 2);
        }
    }

    public function position(array $arguments)
    {
        if (count($arguments) > 0) {
            throw new \InvalidArgumentException("Unexpected argument at index 1.");
        }
    }
}
