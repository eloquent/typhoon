<?php
namespace Typhoon\Eloquent\Typhoon\Configuration;


class ConfigurationOptionTyphoon extends \Typhoon\Validator
{
    public function createUndefinedInstanceException(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 3))
        {
            if (($argumentCount < 1))
            {
                throw (new \Typhoon\Exception\MissingArgumentException('className', 0, 'string'));
            }
            if (($argumentCount < 2))
            {
                throw (new \Typhoon\Exception\MissingArgumentException('property', 1, 'string'));
            }
            throw (new \Typhoon\Exception\MissingArgumentException('value', 2, 'mixed'));
        }
        elseif (($argumentCount > 4))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(4, $arguments[4]));
        }
        ($value = $arguments[0]);
        if ((!\is_string($value)))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'className',
                0,
                $arguments[0],
                'string'
            ));
        }
        ($value = $arguments[1]);
        if ((!\is_string($value)))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'property',
                1,
                $arguments[1],
                'string'
            ));
        }
    }
}
