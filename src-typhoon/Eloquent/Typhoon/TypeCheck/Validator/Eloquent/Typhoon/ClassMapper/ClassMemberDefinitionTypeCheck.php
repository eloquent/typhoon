<?php
namespace Eloquent\Typhoon\TypeCheck\Validator\Eloquent\Typhoon\ClassMapper;

class ClassMemberDefinitionTypeCheck extends \Eloquent\Typhoon\TypeCheck\AbstractValidator
{
    public function compare(array $arguments)
    {
        $argumentCount = \count($arguments);
        if ($argumentCount < 2) {
            if ($argumentCount < 1) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('left', 0, 'Eloquent\\Typhoon\\ClassMapper\\ClassMemberDefinition');
            }
            throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('right', 1, 'Eloquent\\Typhoon\\ClassMapper\\ClassMemberDefinition');
        } elseif ($argumentCount > 2) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(2, $arguments[2]);
        }
    }

    public function compareTuples(array $arguments)
    {
        $argumentCount = \count($arguments);
        if ($argumentCount < 2) {
            if ($argumentCount < 1) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('left', 0, 'tuple<Eloquent\\Typhoon\\ClassMapper\\ClassDefinition, Eloquent\\Typhoon\\ClassMapper\\ClassMemberDefinition>');
            }
            throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('right', 1, 'tuple<Eloquent\\Typhoon\\ClassMapper\\ClassDefinition, Eloquent\\Typhoon\\ClassMapper\\ClassMemberDefinition>');
        } elseif ($argumentCount > 2) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(2, $arguments[2]);
        }
        $value = $arguments[0];
        if (!(\is_array($value) && \array_keys($value) === \range(0, 1) && $value[0] instanceof \Eloquent\Typhoon\ClassMapper\ClassDefinition && $value[1] instanceof \Eloquent\Typhoon\ClassMapper\ClassMemberDefinition)) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                'left',
                0,
                $arguments[0],
                'tuple<Eloquent\\Typhoon\\ClassMapper\\ClassDefinition, Eloquent\\Typhoon\\ClassMapper\\ClassMemberDefinition>'
            );
        }
        $value = $arguments[1];
        if (!(\is_array($value) && \array_keys($value) === \range(0, 1) && $value[0] instanceof \Eloquent\Typhoon\ClassMapper\ClassDefinition && $value[1] instanceof \Eloquent\Typhoon\ClassMapper\ClassMemberDefinition)) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                'right',
                1,
                $arguments[1],
                'tuple<Eloquent\\Typhoon\\ClassMapper\\ClassDefinition, Eloquent\\Typhoon\\ClassMapper\\ClassMemberDefinition>'
            );
        }
    }

    public function validateConstruct(array $arguments)
    {
        $argumentCount = \count($arguments);
        if ($argumentCount < 5) {
            if ($argumentCount < 1) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('name', 0, 'string');
            }
            if ($argumentCount < 2) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('isStatic', 1, 'boolean');
            }
            if ($argumentCount < 3) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('accessModifier', 2, 'Icecave\\Pasta\\AST\\Type\\AccessModifier');
            }
            if ($argumentCount < 4) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('lineNumber', 3, 'integer');
            }
            throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('source', 4, 'string');
        } elseif ($argumentCount > 5) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(5, $arguments[5]);
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
        $value = $arguments[3];
        if (!\is_int($value)) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                'lineNumber',
                3,
                $arguments[3],
                'integer'
            );
        }
        $value = $arguments[4];
        if (!\is_string($value)) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                'source',
                4,
                $arguments[4],
                'string'
            );
        }
    }

    public function name(array $arguments)
    {
        if (\count($arguments) > 0) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(0, $arguments[0]);
        }
    }

    public function isStatic(array $arguments)
    {
        if (\count($arguments) > 0) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(0, $arguments[0]);
        }
    }

    public function accessModifier(array $arguments)
    {
        if (\count($arguments) > 0) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(0, $arguments[0]);
        }
    }

    public function lineNumber(array $arguments)
    {
        if (\count($arguments) > 0) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(0, $arguments[0]);
        }
    }

    public function source(array $arguments)
    {
        if (\count($arguments) > 0) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(0, $arguments[0]);
        }
    }

    public function endLineNumber(array $arguments)
    {
        if (\count($arguments) > 0) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(0, $arguments[0]);
        }
    }

}
