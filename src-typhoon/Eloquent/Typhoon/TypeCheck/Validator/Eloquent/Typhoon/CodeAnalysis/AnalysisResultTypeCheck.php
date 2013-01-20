<?php
namespace Eloquent\Typhoon\TypeCheck\Validator\Eloquent\Typhoon\CodeAnalysis;

class AnalysisResultTypeCheck extends \Eloquent\Typhoon\TypeCheck\AbstractValidator
{
    public function validateConstruct(array $arguments)
    {
        $argumentCount = \count($arguments);
        if ($argumentCount < 3) {
            if ($argumentCount < 1) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('classesMissingConstructorCall', 0, 'array<Eloquent\\Typhoon\\ClassMapper\\ClassDefinition>');
            }
            if ($argumentCount < 2) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('classesMissingProperty', 1, 'array<Eloquent\\Typhoon\\ClassMapper\\ClassDefinition>');
            }
            throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('methodsMissingCall', 2, 'array<tuple<Eloquent\\Typhoon\\ClassMapper\\ClassDefinition, Eloquent\\Typhoon\\ClassMapper\\MethodDefinition>>');
        } elseif ($argumentCount > 3) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(3, $arguments[3]);
        }
        $value = $arguments[0];
        $check = function ($value) {
            if (!\is_array($value)) {
                return false;
            }
            foreach ($value as $key => $subValue) {
                if (!$subValue instanceof \Eloquent\Typhoon\ClassMapper\ClassDefinition) {
                    return false;
                }
            }
            return true;
        };
        if (!$check($arguments[0])) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                'classesMissingConstructorCall',
                0,
                $arguments[0],
                'array<Eloquent\\Typhoon\\ClassMapper\\ClassDefinition>'
            );
        }
        $value = $arguments[1];
        $check = function ($value) {
            if (!\is_array($value)) {
                return false;
            }
            foreach ($value as $key => $subValue) {
                if (!$subValue instanceof \Eloquent\Typhoon\ClassMapper\ClassDefinition) {
                    return false;
                }
            }
            return true;
        };
        if (!$check($arguments[1])) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                'classesMissingProperty',
                1,
                $arguments[1],
                'array<Eloquent\\Typhoon\\ClassMapper\\ClassDefinition>'
            );
        }
        $value = $arguments[2];
        $check = function ($value) {
            if (!\is_array($value)) {
                return false;
            }
            foreach ($value as $key => $subValue) {
                if (!(\is_array($subValue) && \array_keys($subValue) === \range(0, 1) && $subValue[0] instanceof \Eloquent\Typhoon\ClassMapper\ClassDefinition && $subValue[1] instanceof \Eloquent\Typhoon\ClassMapper\MethodDefinition)) {
                    return false;
                }
            }
            return true;
        };
        if (!$check($arguments[2])) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                'methodsMissingCall',
                2,
                $arguments[2],
                'array<tuple<Eloquent\\Typhoon\\ClassMapper\\ClassDefinition, Eloquent\\Typhoon\\ClassMapper\\MethodDefinition>>'
            );
        }
    }

    public function classesMissingConstructorCall(array $arguments)
    {
        if (\count($arguments) > 0) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(0, $arguments[0]);
        }
    }

    public function classesMissingProperty(array $arguments)
    {
        if (\count($arguments) > 0) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(0, $arguments[0]);
        }
    }

    public function methodsMissingCall(array $arguments)
    {
        if (\count($arguments) > 0) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(0, $arguments[0]);
        }
    }

    public function isSuccessful(array $arguments)
    {
        if (\count($arguments) > 0) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(0, $arguments[0]);
        }
    }

    public function count(array $arguments)
    {
        if (\count($arguments) > 0) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(0, $arguments[0]);
        }
    }

}
