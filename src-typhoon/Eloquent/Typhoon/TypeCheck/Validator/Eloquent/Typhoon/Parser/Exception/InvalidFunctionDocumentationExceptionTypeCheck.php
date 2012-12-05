<?php
namespace Eloquent\Typhoon\TypeCheck\Validator\Eloquent\Typhoon\Parser\Exception;


class InvalidFunctionDocumentationExceptionTypeCheck extends \Eloquent\Typhoon\TypeCheck\AbstractValidator
{
    public function validateConstruct(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 1))
        {
            throw (new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('functionName', 0, 'string'));
        }
        elseif (($argumentCount > 2))
        {
            throw (new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(2, $arguments[2]));
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
    }
    public function functionName(array $arguments)
    {
        if ((\count($arguments) > 0))
        {
            throw (new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(0, $arguments[0]));
        }
    }
}
