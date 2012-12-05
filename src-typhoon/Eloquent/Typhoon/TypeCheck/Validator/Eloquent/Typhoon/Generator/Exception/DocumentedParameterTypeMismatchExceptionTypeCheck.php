<?php
namespace Eloquent\Typhoon\TypeCheck\Validator\Eloquent\Typhoon\Generator\Exception;


class DocumentedParameterTypeMismatchExceptionTypeCheck extends \Eloquent\Typhoon\TypeCheck\AbstractValidator
{
    public function validateConstruct(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 4))
        {
            if (($argumentCount < 1))
            {
                throw (new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('functionName', 0, 'string'));
            }
            if (($argumentCount < 2))
            {
                throw (new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('parameterName', 1, 'string'));
            }
            if (($argumentCount < 3))
            {
                throw (new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('documentedType', 2, 'Eloquent\\Typhax\\Type\\Type'));
            }
            throw (new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('nativeType', 3, 'Eloquent\\Typhax\\Type\\Type'));
        }
        elseif (($argumentCount > 6))
        {
            throw (new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(6, $arguments[6]));
        }
        ($value = $arguments[0]);
        if ((!\is_string($value)))
        {
            throw (new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                'functionName',
                0,
                $arguments[0],
                'string'
            ));
        }
        ($value = $arguments[1]);
        if ((!\is_string($value)))
        {
            throw (new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                'parameterName',
                1,
                $arguments[1],
                'string'
            ));
        }
    }
    public function functionName(array $arguments)
    {
        if ((\count($arguments) > 0))
        {
            throw (new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(0, $arguments[0]));
        }
    }
    public function parameterName(array $arguments)
    {
        if ((\count($arguments) > 0))
        {
            throw (new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(0, $arguments[0]));
        }
    }
    public function documentedType(array $arguments)
    {
        if ((\count($arguments) > 0))
        {
            throw (new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(0, $arguments[0]));
        }
    }
    public function nativeType(array $arguments)
    {
        if ((\count($arguments) > 0))
        {
            throw (new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(0, $arguments[0]));
        }
    }
    public function typeRenderer(array $arguments)
    {
        if ((\count($arguments) > 0))
        {
            throw (new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(0, $arguments[0]));
        }
    }
}
