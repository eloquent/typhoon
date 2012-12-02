<?php
namespace Eloquent\Typhoon\Validators\Validator\Eloquent\Typhoon\Parser\Exception;


class InvalidFunctionDocumentationExceptionTyphoon extends \Eloquent\Typhoon\Validators\AbstractValidator
{
    public function validateConstruct(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 1))
        {
            throw (new \Typhoon\Exception\MissingArgumentException('functionName', 0, 'string'));
        }
        elseif (($argumentCount > 2))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(2, $arguments[2]));
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
    public function functionName(array $arguments)
    {
        if ((\count($arguments) > 0))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(0, $arguments[0]));
        }
    }
}
