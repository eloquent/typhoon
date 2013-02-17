<?php
namespace Eloquent\Typhoon\TypeCheck\Validator\Eloquent\Typhoon\CodeAnalysis\Issue\ParameterIssue;

class DocumentedParameterNameMismatchTypeCheck extends \Eloquent\Typhoon\TypeCheck\AbstractValidator
{
    public function validateConstruct(array $arguments)
    {
        $argumentCount = \count($arguments);
        if ($argumentCount < 4) {
            if ($argumentCount < 1) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('classDefinition', 0, 'Eloquent\\Typhoon\\ClassMapper\\ClassDefinition');
            }
            if ($argumentCount < 2) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('methodDefinition', 1, 'Eloquent\\Typhoon\\ClassMapper\\MethodDefinition');
            }
            if ($argumentCount < 3) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('parameterName', 2, 'string');
            }
            throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('documentedParameterName', 3, 'string');
        } elseif ($argumentCount > 5) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(5, $arguments[5]);
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
        $value = $arguments[3];
        if (!\is_string($value)) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                'documentedParameterName',
                3,
                $arguments[3],
                'string'
            );
        }
    }

    public function documentedParameterName(array $arguments)
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
