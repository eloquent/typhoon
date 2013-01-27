<?php
namespace Eloquent\Typhoon\TypeCheck\Validator\Eloquent\Typhoon\Generator\ParameterListMerge;

class MergeToolTypeCheck extends \Eloquent\Typhoon\TypeCheck\AbstractValidator
{
    public function validateConstruct(array $arguments)
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
        if ($argumentCount < 4) {
            if ($argumentCount < 1) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('configuration', 0, 'Eloquent\\Typhoon\\Configuration\\RuntimeConfiguration');
            }
            if ($argumentCount < 2) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('functionName', 1, 'string');
            }
            if ($argumentCount < 3) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('documentedParameterList', 2, 'Eloquent\\Typhoon\\Parameter\\ParameterList');
            }
            throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('nativeParameterList', 3, 'Eloquent\\Typhoon\\Parameter\\ParameterList');
        } elseif ($argumentCount > 4) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(4, $arguments[4]);
        }
        $value = $arguments[1];
        if (!\is_string($value)) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                'functionName',
                1,
                $arguments[1],
                'string'
            );
        }
    }

    public function mergeParameter(array $arguments)
    {
        $argumentCount = \count($arguments);
        if ($argumentCount < 4) {
            if ($argumentCount < 1) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('configuration', 0, 'Eloquent\\Typhoon\\Configuration\\RuntimeConfiguration');
            }
            if ($argumentCount < 2) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('functionName', 1, 'string');
            }
            if ($argumentCount < 3) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('documentedParameter', 2, 'Eloquent\\Typhoon\\Parameter\\Parameter');
            }
            throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('nativeParameter', 3, 'Eloquent\\Typhoon\\Parameter\\Parameter');
        } elseif ($argumentCount > 4) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(4, $arguments[4]);
        }
        $value = $arguments[1];
        if (!\is_string($value)) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                'functionName',
                1,
                $arguments[1],
                'string'
            );
        }
    }

    public function mergeType(array $arguments)
    {
        $argumentCount = \count($arguments);
        if ($argumentCount < 5) {
            if ($argumentCount < 1) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('configuration', 0, 'Eloquent\\Typhoon\\Configuration\\RuntimeConfiguration');
            }
            if ($argumentCount < 2) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('functionName', 1, 'string');
            }
            if ($argumentCount < 3) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('parameterName', 2, 'string');
            }
            if ($argumentCount < 4) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('documentedType', 3, 'Eloquent\\Typhax\\Type\\Type');
            }
            throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('nativeType', 4, 'Eloquent\\Typhax\\Type\\Type');
        } elseif ($argumentCount > 5) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(5, $arguments[5]);
        }
        $value = $arguments[1];
        if (!\is_string($value)) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                'functionName',
                1,
                $arguments[1],
                'string'
            );
        }
        $value = $arguments[2];
        if (!\is_string($value)) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                'parameterName',
                2,
                $arguments[2],
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

}
