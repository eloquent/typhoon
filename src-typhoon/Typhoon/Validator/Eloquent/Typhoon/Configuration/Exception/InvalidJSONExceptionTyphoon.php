<?php
namespace Typhoon\Validator\Eloquent\Typhoon\Configuration\Exception;


class InvalidJSONExceptionTyphoon extends \Typhoon\AbstractValidator
{
    public function validateConstruct(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 2))
        {
            if (($argumentCount < 1))
            {
                throw (new \Typhoon\Exception\MissingArgumentException('jsonErrorCode', 0, 'integer'));
            }
            throw (new \Typhoon\Exception\MissingArgumentException('path', 1, 'string'));
        }
        elseif (($argumentCount > 3))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(3, $arguments[3]));
        }
        ($value = $arguments[0]);
        if ((!\is_int($value)))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'jsonErrorCode',
                0,
                $arguments[0],
                'integer'
            ));
        }
        ($value = $arguments[1]);
        if ((!\is_string($value)))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'path',
                1,
                $arguments[1],
                'string'
            ));
        }
    }
    public function jsonErrorCode(array $arguments)
    {
        if ((\count($arguments) > 0))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(0, $arguments[0]));
        }
    }
    public function jsonErrorMessage(array $arguments)
    {
        if ((\count($arguments) > 0))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(0, $arguments[0]));
        }
    }
    public function path(array $arguments)
    {
        if ((\count($arguments) > 0))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(0, $arguments[0]));
        }
    }
}
