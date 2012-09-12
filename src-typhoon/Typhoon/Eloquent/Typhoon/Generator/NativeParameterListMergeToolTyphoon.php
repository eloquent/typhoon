<?php
namespace Typhoon\Eloquent\Typhoon\Generator;


class NativeParameterListMergeToolTyphoon extends \Typhoon\Validator
{
    public function validateConstruct(array $arguments)
    {
        if ((\count($arguments) > 0))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(0, $arguments[0]));
        }
    }
    public function nativeCallableAvailable(array $arguments)
    {
        if ((\count($arguments) > 0))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(0, $arguments[0]));
        }
    }
    public function setUseNativeCallable(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 1))
        {
            throw (new \Typhoon\Exception\MissingArgumentException('useNativeCallable', 0, 'boolean'));
        }
        elseif (($argumentCount > 1))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(1, $arguments[1]));
        }
        ($value = $arguments[0]);
        if ((!\is_bool($value)))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'useNativeCallable',
                0,
                $arguments[0],
                'boolean'
            ));
        }
    }
    public function useNativeCallable(array $arguments)
    {
        if ((\count($arguments) > 0))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(0, $arguments[0]));
        }
    }
    public function merge(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 3))
        {
            if (($argumentCount < 1))
            {
                throw (new \Typhoon\Exception\MissingArgumentException('functionName', 0, 'string'));
            }
            if (($argumentCount < 2))
            {
                throw (new \Typhoon\Exception\MissingArgumentException('documentedParameterList', 1, 'mixed'));
            }
            throw (new \Typhoon\Exception\MissingArgumentException('nativeParameterList', 2, 'mixed'));
        }
        elseif (($argumentCount > 3))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(3, $arguments[3]));
        }
        ($value = $arguments[0]);
        if ((!\is_string($value)))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'functionName',
                0,
                $arguments[0],
                'string'
            ));
        }
    }
    public function mergeParameter(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 3))
        {
            if (($argumentCount < 1))
            {
                throw (new \Typhoon\Exception\MissingArgumentException('functionName', 0, 'string'));
            }
            if (($argumentCount < 2))
            {
                throw (new \Typhoon\Exception\MissingArgumentException('documentedParameter', 1, 'mixed'));
            }
            throw (new \Typhoon\Exception\MissingArgumentException('nativeParameter', 2, 'mixed'));
        }
        elseif (($argumentCount > 3))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(3, $arguments[3]));
        }
        ($value = $arguments[0]);
        if ((!\is_string($value)))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'functionName',
                0,
                $arguments[0],
                'string'
            ));
        }
    }
    public function mergeType(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 4))
        {
            if (($argumentCount < 1))
            {
                throw (new \Typhoon\Exception\MissingArgumentException('functionName', 0, 'string'));
            }
            if (($argumentCount < 2))
            {
                throw (new \Typhoon\Exception\MissingArgumentException('parameterName', 1, 'string'));
            }
            if (($argumentCount < 3))
            {
                throw (new \Typhoon\Exception\MissingArgumentException('documentedType', 2, 'mixed'));
            }
            throw (new \Typhoon\Exception\MissingArgumentException('nativeType', 3, 'mixed'));
        }
        elseif (($argumentCount > 4))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(4, $arguments[4]));
        }
        ($value = $arguments[0]);
        if ((!\is_string($value)))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'functionName',
                0,
                $arguments[0],
                'string'
            ));
        }
        ($value = $arguments[1]);
        if ((!\is_string($value)))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'parameterName',
                1,
                $arguments[1],
                'string'
            ));
        }
    }
    public function typeIsCompatible(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 2))
        {
            if (($argumentCount < 1))
            {
                throw (new \Typhoon\Exception\MissingArgumentException('documentedType', 0, 'mixed'));
            }
            throw (new \Typhoon\Exception\MissingArgumentException('nativeType', 1, 'mixed'));
        }
        elseif (($argumentCount > 2))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(2, $arguments[2]));
        }
    }
}
