<?php
namespace Typhoon\Eloquent\Typhoon\Compiler;

class ParameterListCompilerTyphoon
{
    public function validateConstructor(array $arguments)
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

    public function typhaxCompiler(array $arguments)
    {
        if (count($arguments) > 0) {
            throw new \InvalidArgumentException("Unexpected argument at index 1.");
        }
    }

    public function visitParameter(array $arguments)
    {
        $argumentCount = count($arguments);
        if ($argumentCount < 1) {
            throw new \InvalidArgumentException("Missing argument for parameter 'parameter'.");
        } elseif ($argumentCount > 1) {
            throw new \InvalidArgumentException("Unexpected argument at index 2.");
        }

        $check = function($argument, $index) {
            $check = function($value) {
                return $value instanceof \Eloquent\Typhoon\Parameter\Parameter;
            };
            if (!$check($argument)) {
                throw new \InvalidArgumentException("Unexpected argument for parameter 'parameter' at index ".$index.".");
            }
        };
        $check($arguments[0], 0);
    }

    public function visitParameterList(array $arguments)
    {
        $argumentCount = count($arguments);
        if ($argumentCount < 1) {
            throw new \InvalidArgumentException("Missing argument for parameter 'parameterList'.");
        } elseif ($argumentCount > 1) {
            throw new \InvalidArgumentException("Unexpected argument at index 2.");
        }

        $check = function($argument, $index) {
            $check = function($value) {
                return $value instanceof \Eloquent\Typhoon\Parameter\ParameterList;
            };
            if (!$check($argument)) {
                throw new \InvalidArgumentException("Unexpected argument for parameter 'parameterList' at index ".$index.".");
            }
        };
        $check($arguments[0], 0);
    }

    public function createCallback(array $arguments)
    {
        $argumentCount = count($arguments);
        if ($argumentCount < 1) {
            throw new \InvalidArgumentException("Missing argument for parameter 'content'.");
        } elseif ($argumentCount > 1) {
            throw new \InvalidArgumentException("Unexpected argument at index 2.");
        }

        $check = function($argument, $index) {
            $check = function($value) {
                return is_string($value);
            };
            if (!$check($argument)) {
                throw new \InvalidArgumentException("Unexpected argument for parameter 'content' at index ".$index.".");
            }
        };
        $check($arguments[0], 0);
    }

    public function indent(array $arguments)
    {
        $argumentCount = count($arguments);
        if ($argumentCount < 1) {
            throw new \InvalidArgumentException("Missing argument for parameter 'content'.");
        } elseif ($argumentCount > 2) {
            throw new \InvalidArgumentException("Unexpected argument at index 3.");
        }

        $check = function($argument, $index) {
            $check = function($value) {
                return is_string($value);
            };
            if (!$check($argument)) {
                throw new \InvalidArgumentException("Unexpected argument for parameter 'content' at index ".$index.".");
            }
        };
        $check($arguments[0], 0);

        if ($argumentCount > 1) {
            $check = function($argument, $index) {
                $check = function($value) {
                    return is_integer($value);
                };
                if (!$check($argument)) {
                    throw new \InvalidArgumentException("Unexpected argument for parameter 'depth' at index ".$index.".");
                }
            };
            $check($arguments[1], 1);
        }
    }
}