<?php
namespace Eloquent\Typhoon\TypeCheck\Validator\Eloquent\Typhoon\CodeAnalysis\Issue\ClassIssue;

class MissingPropertyTypeCheck extends \Eloquent\Typhoon\TypeCheck\AbstractValidator
{
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
