<?php
namespace Eloquent\Typhoon\TypeCheck\Validator\Eloquent\Typhoon\ClassMapper;


class MethodDefinitionTypeCheck extends \Eloquent\Typhoon\TypeCheck\AbstractValidator
{
    public function validateConstruct(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 4))
        {
            if (($argumentCount < 1))
            {
                throw (new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('methodName', 0, 'string'));
            }
            if (($argumentCount < 2))
            {
                throw (new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('isStatic', 1, 'boolean'));
            }
            if (($argumentCount < 3))
            {
                throw (new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('lineNumber', 2, 'integer'));
            }
            throw (new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('source', 3, 'string'));
        }
        elseif (($argumentCount > 4))
        {
            throw (new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(4, $arguments[4]));
        }
        ($value = $arguments[0]);
        if ((!\is_string($value)))
        {
            throw (new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                'methodName',
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
        ($value = $arguments[2]);
        if ((!\is_int($value)))
        {
            throw (new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                'lineNumber',
                2,
                $arguments[2],
                'integer'
            ));
        }
        ($value = $arguments[3]);
        if ((!\is_string($value)))
        {
            throw (new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                'source',
                3,
                $arguments[3],
                'string'
            ));
        }
    }
    public function methodName(array $arguments)
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
    public function lineNumber(array $arguments)
    {
        if ((\count($arguments) > 0))
        {
            throw (new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(0, $arguments[0]));
        }
    }
    public function source(array $arguments)
    {
        if ((\count($arguments) > 0))
        {
            throw (new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(0, $arguments[0]));
        }
    }
}
