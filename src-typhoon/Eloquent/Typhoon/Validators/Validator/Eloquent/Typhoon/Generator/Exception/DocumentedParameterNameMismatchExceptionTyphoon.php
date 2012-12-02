<?php
namespace Eloquent\Typhoon\Validators\Validator\Eloquent\Typhoon\Generator\Exception;


class DocumentedParameterNameMismatchExceptionTyphoon extends \Eloquent\Typhoon\Validators\AbstractValidator
{
    public function validateConstruct(array $arguments)
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
                throw (new \Typhoon\Exception\MissingArgumentException('documentedParameterName', 1, 'string'));
            }
            throw (new \Typhoon\Exception\MissingArgumentException('nativeParameterName', 2, 'string'));
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
                'documentedParameterName',
                1,
                $arguments[1],
                'string'
            ));
        }
        ($value = $arguments[2]);
        if ((!\is_string($value)))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'nativeParameterName',
                2,
                $arguments[2],
                'string'
            ));
        }
    }
    public function functionName(array $arguments)
    {
        if ((\count($arguments) > 0))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(0, $arguments[0]));
        }
    }
    public function documentedParameterName(array $arguments)
    {
        if ((\count($arguments) > 0))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(0, $arguments[0]));
        }
    }
    public function nativeParameterName(array $arguments)
    {
        if ((\count($arguments) > 0))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(0, $arguments[0]));
        }
    }
}
