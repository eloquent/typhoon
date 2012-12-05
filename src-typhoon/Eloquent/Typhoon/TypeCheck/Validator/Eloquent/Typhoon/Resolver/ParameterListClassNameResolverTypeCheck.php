<?php
namespace Eloquent\Typhoon\TypeCheck\Validator\Eloquent\Typhoon\Resolver;


class ParameterListClassNameResolverTypeCheck extends \Eloquent\Typhoon\TypeCheck\AbstractValidator
{
    public function validateConstruct(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 1))
        {
            throw (new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('typeResolver', 0, 'Eloquent\\Typhax\\Resolver\\ObjectTypeClassNameResolver'));
        }
        elseif (($argumentCount > 1))
        {
            throw (new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(1, $arguments[1]));
        }
    }
    public function typeResolver(array $arguments)
    {
        if ((\count($arguments) > 0))
        {
            throw (new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(0, $arguments[0]));
        }
    }
    public function visitParameter(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 1))
        {
            throw (new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('parameter', 0, 'Eloquent\\Typhoon\\Parameter\\Parameter'));
        }
        elseif (($argumentCount > 1))
        {
            throw (new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(1, $arguments[1]));
        }
    }
    public function visitParameterList(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 1))
        {
            throw (new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('parameterList', 0, 'Eloquent\\Typhoon\\Parameter\\ParameterList'));
        }
        elseif (($argumentCount > 1))
        {
            throw (new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(1, $arguments[1]));
        }
    }
}
