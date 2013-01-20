<?php
namespace Eloquent\Typhoon\TypeCheck\Validator\Eloquent\Typhoon\CodeAnalysis;

class ProjectAnalyzerTypeCheck extends \Eloquent\Typhoon\TypeCheck\AbstractValidator
{
    public function validateConstruct(array $arguments)
    {
        $argumentCount = \count($arguments);
        if ($argumentCount > 1) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(1, $arguments[1]);
        }
    }

    public function classMapper(array $arguments)
    {
        if (\count($arguments) > 0) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(0, $arguments[0]);
        }
    }

    public function analyze(array $arguments)
    {
        $argumentCount = \count($arguments);
        if ($argumentCount < 1) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('configuration', 0, 'Eloquent\\Typhoon\\Configuration\\Configuration');
        } elseif ($argumentCount > 1) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(1, $arguments[1]);
        }
    }

    public function analyzeClass(array $arguments)
    {
        $argumentCount = \count($arguments);
        if ($argumentCount < 5) {
            if ($argumentCount < 1) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('classDefinition', 0, 'Eloquent\\Typhoon\\ClassMapper\\ClassDefinition');
            }
            if ($argumentCount < 2) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('facadeClassName', 1, 'Eloquent\\Cosmos\\ClassName');
            }
            if ($argumentCount < 3) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('classesMissingConstructorCall', 2, 'array<Eloquent\\Typhoon\\ClassMapper\\ClassDefinition>');
            }
            if ($argumentCount < 4) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('classesMissingProperty', 3, 'array<Eloquent\\Typhoon\\ClassMapper\\ClassDefinition>');
            }
            throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('methodsMissingCall', 4, 'array<tuple<Eloquent\\Typhoon\\ClassMapper\\ClassDefinition, Eloquent\\Typhoon\\ClassMapper\\MethodDefinition>>');
        } elseif ($argumentCount > 5) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(5, $arguments[5]);
        }
        $value = $arguments[2];
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
        if (!$check($arguments[2])) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                'classesMissingConstructorCall',
                2,
                $arguments[2],
                'array<Eloquent\\Typhoon\\ClassMapper\\ClassDefinition>'
            );
        }
        $value = $arguments[3];
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
        if (!$check($arguments[3])) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                'classesMissingProperty',
                3,
                $arguments[3],
                'array<Eloquent\\Typhoon\\ClassMapper\\ClassDefinition>'
            );
        }
        $value = $arguments[4];
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
        if (!$check($arguments[4])) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                'methodsMissingCall',
                4,
                $arguments[4],
                'array<tuple<Eloquent\\Typhoon\\ClassMapper\\ClassDefinition, Eloquent\\Typhoon\\ClassMapper\\MethodDefinition>>'
            );
        }
    }

    public function analyzeConstructor(array $arguments)
    {
        $argumentCount = \count($arguments);
        if ($argumentCount < 2) {
            if ($argumentCount < 1) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('methodDefinition', 0, 'Eloquent\\Typhoon\\ClassMapper\\MethodDefinition');
            }
            throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('expectedfacadeClassName', 1, 'Eloquent\\Cosmos\\ClassName');
        } elseif ($argumentCount > 2) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(2, $arguments[2]);
        }
    }

    public function parseFirstMethodStatement(array $arguments)
    {
        $argumentCount = \count($arguments);
        if ($argumentCount < 1) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('source', 0, 'string');
        } elseif ($argumentCount > 1) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(1, $arguments[1]);
        }
        $value = $arguments[0];
        if (!\is_string($value)) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                'source',
                0,
                $arguments[0],
                'string'
            );
        }
    }

    public function normalizeToken(array $arguments)
    {
        $argumentCount = \count($arguments);
        if ($argumentCount < 1) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('token', 0, 'string|tuple<integer, string, integer>');
        } elseif ($argumentCount > 1) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(1, $arguments[1]);
        }
        $value = $arguments[0];
        if (!(\is_string($value) || \is_array($value) && \array_keys($value) === \range(0, 2) && \is_int($value[0]) && \is_string($value[1]) && \is_int($value[2]))) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                'token',
                0,
                $arguments[0],
                'string|tuple<integer, string, integer>'
            );
        }
    }

}
