<?php
namespace Typhoon\Eloquent\Typhoon\Configuration;


class RuntimeConfigurationTyphoon extends \Typhoon\Validator
{
    public function validateConstruct(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount > 2))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(2, $arguments[2]));
        }
        if (($argumentCount > 0))
        {
            ($value = $arguments[0]);
            if ((!(\is_bool($value) || ($value === null))))
            {
                throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                    'useNativeCallable',
                    0,
                    $arguments[0],
                    'boolean|null'
                ));
            }
        }
        if (($argumentCount > 1))
        {
            ($value = $arguments[1]);
            if ((!(\is_bool($value) || ($value === null))))
            {
                throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                    'runtimeGeneration',
                    1,
                    $arguments[1],
                    'boolean|null'
                ));
            }
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
    public function setRuntimeGeneration(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 1))
        {
            throw (new \Typhoon\Exception\MissingArgumentException('runtimeGeneration', 0, 'boolean'));
        }
        elseif (($argumentCount > 1))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(1, $arguments[1]));
        }
        ($value = $arguments[0]);
        if ((!\is_bool($value)))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'runtimeGeneration',
                0,
                $arguments[0],
                'boolean'
            ));
        }
    }
    public function runtimeGeneration(array $arguments)
    {
        if ((\count($arguments) > 0))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(0, $arguments[0]));
        }
    }
}
