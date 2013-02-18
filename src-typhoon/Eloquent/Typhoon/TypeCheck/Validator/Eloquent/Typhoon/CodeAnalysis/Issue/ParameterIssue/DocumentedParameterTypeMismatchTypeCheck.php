<?php
namespace Eloquent\Typhoon\TypeCheck\Validator\Eloquent\Typhoon\CodeAnalysis\Issue\ParameterIssue;

class DocumentedParameterTypeMismatchTypeCheck extends \Eloquent\Typhoon\TypeCheck\AbstractValidator
{
    public function validateConstruct(array $arguments)
    {
        $argumentCount = \count($arguments);
        if ($argumentCount < 5) {
            if ($argumentCount < 1) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('classDefinition', 0, 'Eloquent\\Typhoon\\ClassMapper\\ClassDefinition');
            }
            if ($argumentCount < 2) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('methodDefinition', 1, 'Eloquent\\Typhoon\\ClassMapper\\MethodDefinition');
            }
            if ($argumentCount < 3) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('parameterName', 2, 'string');
            }
            if ($argumentCount < 4) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('type', 3, 'Eloquent\\Typhax\\Type\\Type');
            }
            throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('documentedType', 4, 'Eloquent\\Typhax\\Type\\Type');
        } elseif ($argumentCount > 6) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(6, $arguments[6]);
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

    public function type(array $arguments)
    {
        if (\count($arguments) > 0) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(0, $arguments[0]);
        }
    }

    public function documentedType(array $arguments)
    {
        if (\count($arguments) > 0) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(0, $arguments[0]);
        }
    }

    public function accept(array $arguments)
    {
        $argumentCount = \count($arguments);
        if ($argumentCount < 1) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('visitor', 0, 'Eloquent\\Typhoon\\CodeAnalysis\\Issue\\IssueVisitorInterface');
        } elseif ($argumentCount > 1) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(1, $arguments[1]);
        }
    }

}
