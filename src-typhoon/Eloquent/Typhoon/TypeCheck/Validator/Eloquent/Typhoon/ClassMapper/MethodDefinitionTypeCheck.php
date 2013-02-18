<?php
namespace Eloquent\Typhoon\TypeCheck\Validator\Eloquent\Typhoon\ClassMapper;

class MethodDefinitionTypeCheck extends \Eloquent\Typhoon\TypeCheck\AbstractValidator
{
    public function validateConstruct(array $arguments)
    {
        $argumentCount = \count($arguments);
        if ($argumentCount < 7) {
            if ($argumentCount < 1) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('className', 0, 'Eloquent\\Cosmos\\ClassName');
            }
            if ($argumentCount < 2) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('name', 1, 'string');
            }
            if ($argumentCount < 3) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('isStatic', 2, 'boolean');
            }
            if ($argumentCount < 4) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('isAbstract', 3, 'boolean');
            }
            if ($argumentCount < 5) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('accessModifier', 4, 'Icecave\\Pasta\\AST\\Type\\AccessModifier');
            }
            if ($argumentCount < 6) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('lineNumber', 5, 'integer');
            }
            throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('source', 6, 'string');
        } elseif ($argumentCount > 7) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(7, $arguments[7]);
        }
        $value = $arguments[1];
        if (!\is_string($value)) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                'name',
                1,
                $arguments[1],
                'string'
            );
        }
        $value = $arguments[2];
        if (!\is_bool($value)) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                'isStatic',
                2,
                $arguments[2],
                'boolean'
            );
        }
        $value = $arguments[3];
        if (!\is_bool($value)) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                'isAbstract',
                3,
                $arguments[3],
                'boolean'
            );
        }
        $value = $arguments[5];
        if (!\is_int($value)) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                'lineNumber',
                5,
                $arguments[5],
                'integer'
            );
        }
        $value = $arguments[6];
        if (!\is_string($value)) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                'source',
                6,
                $arguments[6],
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

    public function createReflector(array $arguments)
    {
        if (\count($arguments) > 0) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(0, $arguments[0]);
        }
    }

}
