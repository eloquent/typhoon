<?php
namespace Eloquent\Typhoon\TypeCheck\Validator\Eloquent\Typhoon\Generator\ParameterListMerge;

class MergeToolTypeCheck extends \Eloquent\Typhoon\TypeCheck\AbstractValidator
{
    public function validateConstruct(array $arguments)
    {
        $argumentCount = \count($arguments);
        if ($argumentCount > 1) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(1, $arguments[1]);
        }
        if ($argumentCount > 0) {
            $value = $arguments[0];
            if (!\is_bool($value)) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                    'throwOnError',
                    0,
                    $arguments[0],
                    'boolean'
                );
            }
        }
    }

    public function throwOnError(array $arguments)
    {
        if (\count($arguments) > 0) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(0, $arguments[0]);
        }
    }

    public function clearIssues(array $arguments)
    {
        if (\count($arguments) > 0) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(0, $arguments[0]);
        }
    }

    public function issues(array $arguments)
    {
        if (\count($arguments) > 0) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(0, $arguments[0]);
        }
    }

    public function nativeCallableAvailable(array $arguments)
    {
        if (\count($arguments) > 0) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(0, $arguments[0]);
        }
    }

    public function useNativeCallable(array $arguments)
    {
        $argumentCount = \count($arguments);
        if ($argumentCount < 1) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('configuration', 0, 'Eloquent\\Typhoon\\Configuration\\RuntimeConfiguration');
        } elseif ($argumentCount > 1) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(1, $arguments[1]);
        }
    }

    public function merge(array $arguments)
    {
        $argumentCount = \count($arguments);
        if ($argumentCount < 5) {
            if ($argumentCount < 1) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('configuration', 0, 'Eloquent\\Typhoon\\Configuration\\RuntimeConfiguration');
            }
            if ($argumentCount < 2) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('classDefinition', 1, 'Eloquent\\Typhoon\\ClassMapper\\ClassDefinition|null');
            }
            if ($argumentCount < 3) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('methodDefinition', 2, 'Eloquent\\Typhoon\\ClassMapper\\MethodDefinition');
            }
            if ($argumentCount < 4) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('documentedParameterList', 3, 'Eloquent\\Typhoon\\Parameter\\ParameterList');
            }
            throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('nativeParameterList', 4, 'Eloquent\\Typhoon\\Parameter\\ParameterList');
        } elseif ($argumentCount > 5) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(5, $arguments[5]);
        }
    }

    public function mergeParameter(array $arguments)
    {
        $argumentCount = \count($arguments);
        if ($argumentCount < 5) {
            if ($argumentCount < 1) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('configuration', 0, 'Eloquent\\Typhoon\\Configuration\\RuntimeConfiguration');
            }
            if ($argumentCount < 2) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('classDefinition', 1, 'Eloquent\\Typhoon\\ClassMapper\\ClassDefinition|null');
            }
            if ($argumentCount < 3) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('methodDefinition', 2, 'Eloquent\\Typhoon\\ClassMapper\\MethodDefinition');
            }
            if ($argumentCount < 4) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('documentedParameter', 3, 'Eloquent\\Typhoon\\Parameter\\Parameter');
            }
            throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('nativeParameter', 4, 'Eloquent\\Typhoon\\Parameter\\Parameter');
        } elseif ($argumentCount > 5) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(5, $arguments[5]);
        }
    }

    public function mergeType(array $arguments)
    {
        $argumentCount = \count($arguments);
        if ($argumentCount < 6) {
            if ($argumentCount < 1) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('configuration', 0, 'Eloquent\\Typhoon\\Configuration\\RuntimeConfiguration');
            }
            if ($argumentCount < 2) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('classDefinition', 1, 'Eloquent\\Typhoon\\ClassMapper\\ClassDefinition|null');
            }
            if ($argumentCount < 3) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('methodDefinition', 2, 'Eloquent\\Typhoon\\ClassMapper\\MethodDefinition');
            }
            if ($argumentCount < 4) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('parameterName', 3, 'string');
            }
            if ($argumentCount < 5) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('documentedType', 4, 'Eloquent\\Typhax\\Type\\Type');
            }
            throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('nativeType', 5, 'Eloquent\\Typhax\\Type\\Type');
        } elseif ($argumentCount > 6) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(6, $arguments[6]);
        }
        $value = $arguments[3];
        if (!\is_string($value)) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                'parameterName',
                3,
                $arguments[3],
                'string'
            );
        }
    }

    public function typeIsCompatible(array $arguments)
    {
        $argumentCount = \count($arguments);
        if ($argumentCount < 3) {
            if ($argumentCount < 1) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('configuration', 0, 'Eloquent\\Typhoon\\Configuration\\RuntimeConfiguration');
            }
            if ($argumentCount < 2) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('documentedType', 1, 'Eloquent\\Typhax\\Type\\Type');
            }
            throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('nativeType', 2, 'Eloquent\\Typhax\\Type\\Type');
        } elseif ($argumentCount > 4) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(4, $arguments[4]);
        }
        if ($argumentCount > 3) {
            $value = $arguments[3];
            if (!\is_int($value)) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                    'depth',
                    3,
                    $arguments[3],
                    'integer'
                );
            }
        }
    }

    public function handleError(array $arguments)
    {
        $argumentCount = \count($arguments);
        if ($argumentCount < 1) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('error', 0, 'Eloquent\\Typhoon\\CodeAnalysis\\Issue\\IssueInterface');
        } elseif ($argumentCount > 1) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(1, $arguments[1]);
        }
    }

}
