<?php
namespace Eloquent\Typhoon\TypeCheck\Validator\Eloquent\Typhoon\Configuration;

class RuntimeConfigurationTypeCheck extends \Eloquent\Typhoon\TypeCheck\AbstractValidator
{
    public function validateConstruct(array $arguments)
    {
        $argumentCount = \count($arguments);
        if ($argumentCount > 2) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(2, $arguments[2]);
        }
        if ($argumentCount > 0) {
            $value = $arguments[0];
            if (!(\is_string($value) || $value === null)) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                    'validatorNamespace',
                    0,
                    $arguments[0],
                    'string|null'
                );
            }
        }
        if ($argumentCount > 1) {
            $value = $arguments[1];
            if (!(\is_bool($value) || $value === null)) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                    'useNativeCallable',
                    1,
                    $arguments[1],
                    'boolean|null'
                );
            }
        }
    }

    public function setValidatorNamespace(array $arguments)
    {
        $argumentCount = \count($arguments);
        if ($argumentCount < 1) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('validatorNamespace', 0, 'string');
        } elseif ($argumentCount > 1) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(1, $arguments[1]);
        }
        $value = $arguments[0];
        if (!\is_string($value)) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                'validatorNamespace',
                0,
                $arguments[0],
                'string'
            );
        }
    }

    public function validatorNamespace(array $arguments)
    {
        if (\count($arguments) > 0) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(0, $arguments[0]);
        }
    }

    public function setUseNativeCallable(array $arguments)
    {
        $argumentCount = \count($arguments);
        if ($argumentCount < 1) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('useNativeCallable', 0, 'boolean');
        } elseif ($argumentCount > 1) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(1, $arguments[1]);
        }
        $value = $arguments[0];
        if (!\is_bool($value)) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                'useNativeCallable',
                0,
                $arguments[0],
                'boolean'
            );
        }
    }

    public function useNativeCallable(array $arguments)
    {
        if (\count($arguments) > 0) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(0, $arguments[0]);
        }
    }

}
