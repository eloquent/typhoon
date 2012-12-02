<?php
namespace Typhoon\Validator\Eloquent\Typhoon\Parser\Exception;


class UnexpectedContentExceptionTyphoon extends \Typhoon\AbstractValidator
{
    public function validateConstruct(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 2))
        {
            if (($argumentCount < 1))
            {
                throw (new \Typhoon\Exception\MissingArgumentException('expected', 0, 'string'));
            }
            throw (new \Typhoon\Exception\MissingArgumentException('position', 1, 'integer'));
        }
        elseif (($argumentCount > 3))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(3, $arguments[3]));
        }
        ($value = $arguments[0]);
        if ((!\is_string($value)))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'expected',
                0,
                $arguments[0],
                'string'
            ));
        }
        ($value = $arguments[1]);
        if ((!\is_int($value)))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'position',
                1,
                $arguments[1],
                'integer'
            ));
        }
    }
    public function expected(array $arguments)
    {
        if ((\count($arguments) > 0))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(0, $arguments[0]));
        }
    }
}
