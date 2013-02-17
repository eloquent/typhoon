<?php
namespace Eloquent\Typhoon\TypeCheck\Validator\Eloquent\Typhoon\CodeAnalysis\Issue;

class AbstractMethodIssueTypeCheck extends \Eloquent\Typhoon\TypeCheck\AbstractValidator
{
    public function validateConstruct(array $arguments)
    {
        $argumentCount = \count($arguments);
        if ($argumentCount < 2) {
            if ($argumentCount < 1) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('classDefinition', 0, 'Eloquent\\Typhoon\\ClassMapper\\ClassDefinition');
            }
            throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('methodDefinition', 1, 'Eloquent\\Typhoon\\ClassMapper\\MethodDefinition');
        } elseif ($argumentCount > 3) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(3, $arguments[3]);
        }
    }

    public function methodDefinition(array $arguments)
    {
        if (\count($arguments) > 0) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(0, $arguments[0]);
        }
    }

}
