<?php
namespace Typhoon\Eloquent\Typhoon\Resolver;

class ParameterListClassNameResolverTyphoon
{
    public function validateConstructor(array $arguments)
    {
        $argumentCount = count($arguments);
        if ($argumentCount < 1) {
            throw new \InvalidArgumentException("Missing argument for parameter 'typeResolver'.");
        } elseif ($argumentCount > 1) {
            throw new \InvalidArgumentException("Unexpected argument at index 2.");
        }

        $check = function($argument, $index) {
            $check = function($value) {
                return $value instanceof \Eloquent\Typhax\Resolver\ObjectTypeClassNameResolver;
            };
            if (!$check($argument)) {
                throw new \InvalidArgumentException("Unexpected argument for parameter 'typeResolver' at index ".$index.".");
            }
        };
        $check($arguments[0], 0);
    }

    public function typeResolver(array $arguments)
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
}