<?php
namespace Eloquent\Typhoon\TypeCheck\Validator\Eloquent\Typhoon\Generator\Exception;


class DocumentedParameterByReferenceMismatchExceptionTypeCheck extends \Eloquent\Typhoon\TypeCheck\AbstractValidator
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
                throw (new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('documentedIsByReference', 2, 'boolean'));
            }
            throw (new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('nativeIsByReference', 3, 'boolean'));
        }
        elseif (($argumentCount > 5))
        {
            throw (new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(5, $arguments[5]));
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
        ($value = $arguments[2]);
        if ((!\is_bool($value)))
        {
            throw (new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                'documentedIsByReference',
                2,
                $arguments[2],
                'boolean'
            ));
        }
        ($value = $arguments[3]);
        if ((!\is_bool($value)))
        {
            throw (new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                'nativeIsByReference',
                3,
                $arguments[3],
                'boolean'
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
    public function documentedIsByReference(array $arguments)
    {
        if ((\count($arguments) > 0))
        {
            throw (new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(0, $arguments[0]));
        }
    }
    public function nativeIsByReference(array $arguments)
    {
        if ((\count($arguments) > 0))
        {
            throw (new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(0, $arguments[0]));
        }
    }
}