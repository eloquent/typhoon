<?php
namespace Eloquent\Typhoon\Validators\Validator\Eloquent\Typhoon\Configuration\Exception;


class UndefinedConfigurationOptionExceptionTyphoon extends \Eloquent\Typhoon\Validators\AbstractValidator
{
    public function validateConstruct(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 1))
        {
            throw (new \Typhoon\Exception\MissingArgumentException('optionName', 0, 'string'));
        }
        elseif (($argumentCount > 2))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(2, $arguments[2]));
        }
        ($value = $arguments[0]);
        if ((!\is_string($value)))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'optionName',
                0,
                $arguments[0],
                'string'
            ));
        }
    }
    public function optionName(array $arguments)
    {
        if ((\count($arguments) > 0))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(0, $arguments[0]));
        }
    }
}
