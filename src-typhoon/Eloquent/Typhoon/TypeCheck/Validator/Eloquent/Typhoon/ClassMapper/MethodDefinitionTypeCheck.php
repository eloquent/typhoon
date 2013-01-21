<?php
namespace Eloquent\Typhoon\TypeCheck\Validator\Eloquent\Typhoon\ClassMapper;

class MethodDefinitionTypeCheck extends \Eloquent\Typhoon\TypeCheck\AbstractValidator
{
    public function validateConstruct(array $arguments)
    {
        $argumentCount = \count($arguments);
        if ($argumentCount < 6) {
            if ($argumentCount < 1) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('name', 0, 'string');
            }
            if ($argumentCount < 2) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('isStatic', 1, 'boolean');
            }
            if ($argumentCount < 3) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('isAbstract', 2, 'boolean');
            }
            if ($argumentCount < 4) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('accessModifier', 3, 'Icecave\\Pasta\\AST\\Type\\AccessModifier');
            }
            if ($argumentCount < 5) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('lineNumber', 4, 'integer');
            }
            throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('source', 5, 'string');
        } elseif ($argumentCount > 6) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(6, $arguments[6]);
        }
        $value = $arguments[0];
        if (!\is_string($value)) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                'name',
                0,
                $arguments[0],
                'string'
            );
        }
        $value = $arguments[1];
        if (!\is_bool($value)) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                'isStatic',
                1,
                $arguments[1],
                'boolean'
            );
        }
        $value = $arguments[2];
        if (!\is_bool($value)) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                'isAbstract',
                2,
                $arguments[2],
                'boolean'
            );
        }
        $value = $arguments[4];
        if (!\is_int($value)) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                'lineNumber',
                4,
                $arguments[4],
                'integer'
            );
        }
        $value = $arguments[5];
        if (!\is_string($value)) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                'source',
                5,
                $arguments[5],
                'string'
            );
        }
    }

    public function isAbstract(array $arguments)
    {
        if (\count($arguments) > 0) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(0, $arguments[0]);
        }
    }

}
