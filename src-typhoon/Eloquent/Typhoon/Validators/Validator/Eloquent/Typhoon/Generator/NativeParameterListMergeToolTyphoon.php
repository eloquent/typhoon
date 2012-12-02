<?php
namespace Eloquent\Typhoon\Validators\Validator\Eloquent\Typhoon\Generator;


class NativeParameterListMergeToolTyphoon extends \Eloquent\Typhoon\Validators\AbstractValidator
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
    public function useNativeCallable(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 1))
        {
            throw (new \Typhoon\Exception\MissingArgumentException('configuration', 0, 'mixed'));
        }
        elseif (($argumentCount > 1))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(1, $arguments[1]));
        }
    }
    public function merge(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 4))
        {
            if (($argumentCount < 1))
            {
                throw (new \Typhoon\Exception\MissingArgumentException('configuration', 0, 'mixed'));
            }
            if (($argumentCount < 2))
            {
                throw (new \Typhoon\Exception\MissingArgumentException('functionName', 1, 'string'));
            }
            if (($argumentCount < 3))
            {
                throw (new \Typhoon\Exception\MissingArgumentException('documentedParameterList', 2, 'mixed'));
            }
            throw (new \Typhoon\Exception\MissingArgumentException('nativeParameterList', 3, 'mixed'));
        }
        elseif (($argumentCount > 4))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(4, $arguments[4]));
        }
        ($value = $arguments[1]);
        if ((!\is_string($value)))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'functionName',
                1,
                $arguments[1],
                'string'
            ));
        }
    }
    public function mergeParameter(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 4))
        {
            if (($argumentCount < 1))
            {
                throw (new \Typhoon\Exception\MissingArgumentException('configuration', 0, 'mixed'));
            }
            if (($argumentCount < 2))
            {
                throw (new \Typhoon\Exception\MissingArgumentException('functionName', 1, 'string'));
            }
            if (($argumentCount < 3))
            {
                throw (new \Typhoon\Exception\MissingArgumentException('documentedParameter', 2, 'mixed'));
            }
            throw (new \Typhoon\Exception\MissingArgumentException('nativeParameter', 3, 'mixed'));
        }
        elseif (($argumentCount > 4))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(4, $arguments[4]));
        }
        ($value = $arguments[1]);
        if ((!\is_string($value)))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'functionName',
                1,
                $arguments[1],
                'string'
            ));
        }
    }
    public function mergeType(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 5))
        {
            if (($argumentCount < 1))
            {
                throw (new \Typhoon\Exception\MissingArgumentException('configuration', 0, 'mixed'));
            }
            if (($argumentCount < 2))
            {
                throw (new \Typhoon\Exception\MissingArgumentException('functionName', 1, 'string'));
            }
            if (($argumentCount < 3))
            {
                throw (new \Typhoon\Exception\MissingArgumentException('parameterName', 2, 'string'));
            }
            if (($argumentCount < 4))
            {
                throw (new \Typhoon\Exception\MissingArgumentException('documentedType', 3, 'mixed'));
            }
            throw (new \Typhoon\Exception\MissingArgumentException('nativeType', 4, 'mixed'));
        }
        elseif (($argumentCount > 5))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(5, $arguments[5]));
        }
        ($value = $arguments[1]);
        if ((!\is_string($value)))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'functionName',
                1,
                $arguments[1],
                'string'
            ));
        }
        ($value = $arguments[2]);
        if ((!\is_string($value)))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'parameterName',
                2,
                $arguments[2],
                'string'
            ));
        }
    }
    public function typeIsCompatible(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 3))
        {
            if (($argumentCount < 1))
            {
                throw (new \Typhoon\Exception\MissingArgumentException('configuration', 0, 'mixed'));
            }
            if (($argumentCount < 2))
            {
                throw (new \Typhoon\Exception\MissingArgumentException('documentedType', 1, 'mixed'));
            }
            throw (new \Typhoon\Exception\MissingArgumentException('nativeType', 2, 'mixed'));
        }
        elseif (($argumentCount > 4))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(4, $arguments[4]));
        }
        if (($argumentCount > 3))
        {
            ($value = $arguments[3]);
            if ((!\is_int($value)))
            {
                throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                    'depth',
                    3,
                    $arguments[3],
                    'integer'
                ));
            }
        }
    }
}
