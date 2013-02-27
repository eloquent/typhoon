<?php
namespace Eloquent\Typhoon\TypeCheck\Validator\Eloquent\Typhoon\CodeAnalysis\Issue;

class IssueSetTypeCheck extends \Eloquent\Typhoon\TypeCheck\AbstractValidator
{
    public function validateConstruct(array $arguments)
    {
        $argumentCount = \count($arguments);
        if ($argumentCount > 1) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(1, $arguments[1]);
        }
        if ($argumentCount > 0) {
            $value = $arguments[0];
            $check = function ($value) {
                if (!\is_array($value)) {
                    return false;
                }
                foreach ($value as $key => $subValue) {
                    if (!$subValue instanceof \Eloquent\Typhoon\CodeAnalysis\Issue\IssueInterface) {
                        return false;
                    }
                }
                return true;
            };
            if (!$check($arguments[0])) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                    'issues',
                    0,
                    $arguments[0],
                    'array<Eloquent\\Typhoon\\CodeAnalysis\\Issue\\IssueInterface>'
                );
            }
        }
    }

    public function issues(array $arguments)
    {
        if (\count($arguments) > 0) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(0, $arguments[0]);
        }
    }

    public function issuesBySeverity(array $arguments)
    {
        $argumentCount = \count($arguments);
        if ($argumentCount < 1) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('severity', 0, 'Eloquent\\Typhoon\\CodeAnalysis\\Issue\\IssueSeverity');
        } elseif ($argumentCount > 1) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(1, $arguments[1]);
        }
    }

    public function classNamesBySeverity(array $arguments)
    {
        $argumentCount = \count($arguments);
        if ($argumentCount < 1) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('severity', 0, 'Eloquent\\Typhoon\\CodeAnalysis\\Issue\\IssueSeverity');
        } elseif ($argumentCount > 1) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(1, $arguments[1]);
        }
    }

    public function classIssuesBySeverityAndClass(array $arguments)
    {
        $argumentCount = \count($arguments);
        if ($argumentCount < 2) {
            if ($argumentCount < 1) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('severity', 0, 'Eloquent\\Typhoon\\CodeAnalysis\\Issue\\IssueSeverity');
            }
            throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('className', 1, 'Eloquent\\Cosmos\\ClassName');
        } elseif ($argumentCount > 2) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(2, $arguments[2]);
        }
    }

    public function methodRelatedIssuesBySeverityAndClass(array $arguments)
    {
        $argumentCount = \count($arguments);
        if ($argumentCount < 2) {
            if ($argumentCount < 1) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('severity', 0, 'Eloquent\\Typhoon\\CodeAnalysis\\Issue\\IssueSeverity');
            }
            throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('className', 1, 'Eloquent\\Cosmos\\ClassName');
        } elseif ($argumentCount > 2) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(2, $arguments[2]);
        }
    }

    public function isError(array $arguments)
    {
        if (\count($arguments) > 0) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(0, $arguments[0]);
        }
    }

}
