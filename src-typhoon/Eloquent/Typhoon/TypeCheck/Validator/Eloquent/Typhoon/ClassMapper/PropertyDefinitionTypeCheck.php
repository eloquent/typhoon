<?php
namespace Eloquent\Typhoon\TypeCheck\Validator\Eloquent\Typhoon\ClassMapper;


class PropertyDefinitionTypeCheck extends \Eloquent\Typhoon\TypeCheck\AbstractValidator
{
    public function validateConstruct(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 4))
        {
            if (($argumentCount < 1))
            {
                throw (new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('propertyName', 0, 'string'));
            }
            if (($argumentCount < 2))
            {
                throw (new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('isStatic', 1, 'boolean'));
            }
            if (($argumentCount < 3))
            {
                throw (new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('accessModifier', 2, 'Icecave\\Pasta\\AST\\Type\\AccessModifier'));
            }
            throw (new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('lineNumber', 3, 'integer'));
        }
        elseif (($argumentCount > 4))
        {
            throw (new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(4, $arguments[4]));
        }
        ($value = $arguments[0]);
        if ((!\is_string($value)))
        {
            throw (new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                'propertyName',
                0,
                $arguments[0],
                'string'
            ));
        }
        ($value = $arguments[1]);
        if ((!\is_bool($value)))
        {
            throw (new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                'isStatic',
                1,
                $arguments[1],
                'boolean'
            ));
        }
        ($value = $arguments[3]);
        if ((!\is_int($value)))
        {
            throw (new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                'lineNumber',
                3,
                $arguments[3],
                'integer'
            ));
        }
    }
    public function propertyName(array $arguments)
    {
        if ((\count($arguments) > 0))
        {
            throw (new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(0, $arguments[0]));
        }
    }
    public function isStatic(array $arguments)
    {
        if ((\count($arguments) > 0))
        {
            throw (new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(0, $arguments[0]));
        }
    }
    public function accessModifier(array $arguments)
    {
        if ((\count($arguments) > 0))
        {
            throw (new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(0, $arguments[0]));
        }
    }
    public function lineNumber(array $arguments)
    {
        if ((\count($arguments) > 0))
        {
            throw (new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(0, $arguments[0]));
        }
    }
}
