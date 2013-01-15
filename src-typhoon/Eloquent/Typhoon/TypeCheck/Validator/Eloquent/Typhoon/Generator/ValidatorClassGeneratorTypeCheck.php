<?php
namespace Eloquent\Typhoon\TypeCheck\Validator\Eloquent\Typhoon\Generator;

class ValidatorClassGeneratorTypeCheck extends \Eloquent\Typhoon\TypeCheck\AbstractValidator
{
    public function validateConstruct(array $arguments)
    {
        $argumentCount = \count($arguments);
        if ($argumentCount > 6) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(6, $arguments[6]);
        }
    }

    public function renderer(array $arguments)
    {
        if (\count($arguments) > 0) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(0, $arguments[0]);
        }
    }

    public function parser(array $arguments)
    {
        if (\count($arguments) > 0) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(0, $arguments[0]);
        }
    }

    public function generator(array $arguments)
    {
        if (\count($arguments) > 0) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(0, $arguments[0]);
        }
    }

    public function classMapper(array $arguments)
    {
        if (\count($arguments) > 0) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(0, $arguments[0]);
        }
    }

    public function nativeMergeTool(array $arguments)
    {
        if (\count($arguments) > 0) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(0, $arguments[0]);
        }
    }

    public function generate(array $arguments)
    {
        $argumentCount = \count($arguments);
        if ($argumentCount < 2) {
            if ($argumentCount < 1) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('configuration', 0, 'Eloquent\\Typhoon\\Configuration\\RuntimeConfiguration');
            }
            throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('classDefinition', 1, 'Eloquent\\Typhoon\\ClassMapper\\ClassDefinition');
        } elseif ($argumentCount > 4) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(4, $arguments[4]);
        }
        if ($argumentCount > 2) {
            $value = $arguments[2];
            if (!(\is_string($value) || $value === null)) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                    'namespaceName',
                    2,
                    $arguments[2],
                    'string|null'
                );
            }
        }
        if ($argumentCount > 3) {
            $value = $arguments[3];
            if (!(\is_string($value) || $value === null)) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                    'className',
                    3,
                    $arguments[3],
                    'string|null'
                );
            }
        }
    }

    public function generateFromSource(array $arguments)
    {
        $argumentCount = \count($arguments);
        if ($argumentCount < 3) {
            if ($argumentCount < 1) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('configuration', 0, 'Eloquent\\Typhoon\\Configuration\\RuntimeConfiguration');
            }
            if ($argumentCount < 2) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('sourceClassName', 1, 'string');
            }
            throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('source', 2, 'string');
        } elseif ($argumentCount > 5) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(5, $arguments[5]);
        }
        $value = $arguments[1];
        if (!\is_string($value)) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                'sourceClassName',
                1,
                $arguments[1],
                'string'
            );
        }
        $value = $arguments[2];
        if (!\is_string($value)) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                'source',
                2,
                $arguments[2],
                'string'
            );
        }
        if ($argumentCount > 3) {
            $value = $arguments[3];
            if (!(\is_string($value) || $value === null)) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                    'namespaceName',
                    3,
                    $arguments[3],
                    'string|null'
                );
            }
        }
        if ($argumentCount > 4) {
            $value = $arguments[4];
            if (!(\is_string($value) || $value === null)) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                    'className',
                    4,
                    $arguments[4],
                    'string|null'
                );
            }
        }
    }

    public function generateFromFile(array $arguments)
    {
        $argumentCount = \count($arguments);
        if ($argumentCount < 3) {
            if ($argumentCount < 1) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('configuration', 0, 'Eloquent\\Typhoon\\Configuration\\RuntimeConfiguration');
            }
            if ($argumentCount < 2) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('sourceClassName', 1, 'string');
            }
            throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('path', 2, 'string');
        } elseif ($argumentCount > 5) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(5, $arguments[5]);
        }
        $value = $arguments[1];
        if (!\is_string($value)) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                'sourceClassName',
                1,
                $arguments[1],
                'string'
            );
        }
        $value = $arguments[2];
        if (!\is_string($value)) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                'path',
                2,
                $arguments[2],
                'string'
            );
        }
        if ($argumentCount > 3) {
            $value = $arguments[3];
            if (!(\is_string($value) || $value === null)) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                    'namespaceName',
                    3,
                    $arguments[3],
                    'string|null'
                );
            }
        }
        if ($argumentCount > 4) {
            $value = $arguments[4];
            if (!(\is_string($value) || $value === null)) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                    'className',
                    4,
                    $arguments[4],
                    'string|null'
                );
            }
        }
    }

    public function generateFromClass(array $arguments)
    {
        $argumentCount = \count($arguments);
        if ($argumentCount < 2) {
            if ($argumentCount < 1) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('configuration', 0, 'Eloquent\\Typhoon\\Configuration\\RuntimeConfiguration');
            }
            throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('class', 1, 'ReflectionClass');
        } elseif ($argumentCount > 4) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(4, $arguments[4]);
        }
        if ($argumentCount > 2) {
            $value = $arguments[2];
            if (!(\is_string($value) || $value === null)) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                    'namespaceName',
                    2,
                    $arguments[2],
                    'string|null'
                );
            }
        }
        if ($argumentCount > 3) {
            $value = $arguments[3];
            if (!(\is_string($value) || $value === null)) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                    'className',
                    3,
                    $arguments[3],
                    'string|null'
                );
            }
        }
    }

    public function generateSyntaxTree(array $arguments)
    {
        $argumentCount = \count($arguments);
        if ($argumentCount < 2) {
            if ($argumentCount < 1) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('configuration', 0, 'Eloquent\\Typhoon\\Configuration\\RuntimeConfiguration');
            }
            throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('classDefinition', 1, 'Eloquent\\Typhoon\\ClassMapper\\ClassDefinition');
        } elseif ($argumentCount > 4) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(4, $arguments[4]);
        }
        if ($argumentCount > 2) {
            $value = $arguments[2];
            if (!(\is_string($value) || $value === null)) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                    'namespaceName',
                    2,
                    $arguments[2],
                    'string|null'
                );
            }
        }
        if ($argumentCount > 3) {
            $value = $arguments[3];
            if (!(\is_string($value) || $value === null)) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                    'className',
                    3,
                    $arguments[3],
                    'string|null'
                );
            }
        }
    }

    public function generateMethod(array $arguments)
    {
        $argumentCount = \count($arguments);
        if ($argumentCount < 3) {
            if ($argumentCount < 1) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('configuration', 0, 'Eloquent\\Typhoon\\Configuration\\RuntimeConfiguration');
            }
            if ($argumentCount < 2) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('method', 1, 'ReflectionMethod');
            }
            throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('classDefinition', 2, 'Eloquent\\Typhoon\\ClassMapper\\ClassDefinition');
        } elseif ($argumentCount > 3) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(3, $arguments[3]);
        }
    }

    public function methods(array $arguments)
    {
        $argumentCount = \count($arguments);
        if ($argumentCount < 1) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('classDefinition', 0, 'Eloquent\\Typhoon\\ClassMapper\\ClassDefinition');
        } elseif ($argumentCount > 1) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(1, $arguments[1]);
        }
    }

    public function validatorClassName(array $arguments)
    {
        $argumentCount = \count($arguments);
        if ($argumentCount < 2) {
            if ($argumentCount < 1) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('configuration', 0, 'Eloquent\\Typhoon\\Configuration\\RuntimeConfiguration');
            }
            throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('classDefinition', 1, 'Eloquent\\Typhoon\\ClassMapper\\ClassDefinition');
        } elseif ($argumentCount > 4) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(4, $arguments[4]);
        }
        if ($argumentCount > 2) {
            $value = $arguments[2];
            if (!(\is_string($value) || $value === null)) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                    'namespaceName',
                    2,
                    $arguments[2],
                    'string|null'
                );
            }
        }
        if ($argumentCount > 3) {
            $value = $arguments[3];
            if (!(\is_string($value) || $value === null)) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                    'className',
                    3,
                    $arguments[3],
                    'string|null'
                );
            }
        }
    }

    public function validatorMethodName(array $arguments)
    {
        $argumentCount = \count($arguments);
        if ($argumentCount < 1) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('method', 0, 'ReflectionMethod');
        } elseif ($argumentCount > 1) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(1, $arguments[1]);
        }
    }

    public function parameterList(array $arguments)
    {
        $argumentCount = \count($arguments);
        if ($argumentCount < 3) {
            if ($argumentCount < 1) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('configuration', 0, 'Eloquent\\Typhoon\\Configuration\\RuntimeConfiguration');
            }
            if ($argumentCount < 2) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('method', 1, 'ReflectionMethod');
            }
            throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('classDefinition', 2, 'Eloquent\\Typhoon\\ClassMapper\\ClassDefinition');
        } elseif ($argumentCount > 3) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(3, $arguments[3]);
        }
    }

    public function classNameResolver(array $arguments)
    {
        $argumentCount = \count($arguments);
        if ($argumentCount < 1) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('classDefinition', 0, 'Eloquent\\Typhoon\\ClassMapper\\ClassDefinition');
        } elseif ($argumentCount > 1) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(1, $arguments[1]);
        }
    }

}
