<?php
namespace Typhoon\Eloquent\Typhoon\Parameter;


class ParameterTyphoon extends \Typhoon\Validator
{
    public function validateConstruct(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 2))
        {
            if (($argumentCount < 1))
            {
                throw (new \Typhoon\Exception\MissingArgumentException('name', 0, 'string'));
            }
            throw (new \Typhoon\Exception\MissingArgumentException('type', 1, 'mixed'));
        }
        elseif (($argumentCount > 5))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(5, $arguments[5]));
        }
        ($value = $arguments[0]);
        if ((!\is_string($value)))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'name',
                0,
                $arguments[0],
                'string'
            ));
        }
        if (($argumentCount > 2))
        {
            ($value = $arguments[2]);
            if ((!(\is_string($value) || ($value === null))))
            {
                throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                    'description',
                    2,
                    $arguments[2],
                    'string|null'
                ));
            }
        }
        if (($argumentCount > 3))
        {
            ($value = $arguments[3]);
            if ((!\is_bool($value)))
            {
                throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                    'optional',
                    3,
                    $arguments[3],
                    'boolean'
                ));
            }
        }
        if (($argumentCount > 4))
        {
            ($value = $arguments[4]);
            if ((!\is_bool($value)))
            {
                throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                    'byReference',
                    4,
                    $arguments[4],
                    'boolean'
                ));
            }
        }
    }
    public function name(array $arguments)
    {
        if ((\count($arguments) > 0))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(0, $arguments[0]));
        }
    }
    public function type(array $arguments)
    {
        if ((\count($arguments) > 0))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(0, $arguments[0]));
        }
    }
    public function description(array $arguments)
    {
        if ((\count($arguments) > 0))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(0, $arguments[0]));
        }
    }
    public function isOptional(array $arguments)
    {
        if ((\count($arguments) > 0))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(0, $arguments[0]));
        }
    }
    public function isByReference(array $arguments)
    {
        if ((\count($arguments) > 0))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(0, $arguments[0]));
        }
    }
}
