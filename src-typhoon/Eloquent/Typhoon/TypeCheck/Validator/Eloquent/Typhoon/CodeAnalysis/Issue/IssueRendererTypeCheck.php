<?php
namespace Eloquent\Typhoon\TypeCheck\Validator\Eloquent\Typhoon\CodeAnalysis\Issue;

class IssueRendererTypeCheck extends \Eloquent\Typhoon\TypeCheck\AbstractValidator
{
    public function validateConstruct(array $arguments)
    {
        $argumentCount = \count($arguments);
        if ($argumentCount > 1) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(1, $arguments[1]);
        }
    }

    public function typeRenderer(array $arguments)
    {
        if (\count($arguments) > 0) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(0, $arguments[0]);
        }
    }

    public function visitMissingConstructorCall(array $arguments)
    {
        $argumentCount = \count($arguments);
        if ($argumentCount < 1) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('issue', 0, 'Eloquent\\Typhoon\\CodeAnalysis\\Issue\\ClassIssue\\MissingConstructorCall');
        } elseif ($argumentCount > 1) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(1, $arguments[1]);
        }
    }

    public function visitMissingProperty(array $arguments)
    {
        $argumentCount = \count($arguments);
        if ($argumentCount < 1) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('issue', 0, 'Eloquent\\Typhoon\\CodeAnalysis\\Issue\\ClassIssue\\MissingProperty');
        } elseif ($argumentCount > 1) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(1, $arguments[1]);
        }
    }

    public function visitInadmissibleMethodCall(array $arguments)
    {
        $argumentCount = \count($arguments);
        if ($argumentCount < 1) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('issue', 0, 'Eloquent\\Typhoon\\CodeAnalysis\\Issue\\MethodIssue\\InadmissibleMethodCall');
        } elseif ($argumentCount > 1) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(1, $arguments[1]);
        }
    }

    public function visitMissingMethodCall(array $arguments)
    {
        $argumentCount = \count($arguments);
        if ($argumentCount < 1) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('issue', 0, 'Eloquent\\Typhoon\\CodeAnalysis\\Issue\\MethodIssue\\MissingMethodCall');
        } elseif ($argumentCount > 1) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(1, $arguments[1]);
        }
    }

    public function visitDefinedParameterVariableLength(array $arguments)
    {
        $argumentCount = \count($arguments);
        if ($argumentCount < 1) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('issue', 0, 'Eloquent\\Typhoon\\CodeAnalysis\\Issue\\ParameterIssue\\DefinedParameterVariableLength');
        } elseif ($argumentCount > 1) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(1, $arguments[1]);
        }
    }

    public function visitDocumentedParameterByReferenceMismatch(array $arguments)
    {
        $argumentCount = \count($arguments);
        if ($argumentCount < 1) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('issue', 0, 'Eloquent\\Typhoon\\CodeAnalysis\\Issue\\ParameterIssue\\DocumentedParameterByReferenceMismatch');
        } elseif ($argumentCount > 1) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(1, $arguments[1]);
        }
    }

    public function visitDocumentedParameterNameMismatch(array $arguments)
    {
        $argumentCount = \count($arguments);
        if ($argumentCount < 1) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('issue', 0, 'Eloquent\\Typhoon\\CodeAnalysis\\Issue\\ParameterIssue\\DocumentedParameterNameMismatch');
        } elseif ($argumentCount > 1) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(1, $arguments[1]);
        }
    }

    public function visitDocumentedParameterTypeMismatch(array $arguments)
    {
        $argumentCount = \count($arguments);
        if ($argumentCount < 1) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('issue', 0, 'Eloquent\\Typhoon\\CodeAnalysis\\Issue\\ParameterIssue\\DocumentedParameterTypeMismatch');
        } elseif ($argumentCount > 1) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(1, $arguments[1]);
        }
    }

    public function visitDocumentedParameterUndefined(array $arguments)
    {
        $argumentCount = \count($arguments);
        if ($argumentCount < 1) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('issue', 0, 'Eloquent\\Typhoon\\CodeAnalysis\\Issue\\ParameterIssue\\DocumentedParameterUndefined');
        } elseif ($argumentCount > 1) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(1, $arguments[1]);
        }
    }

    public function visitUndocumentedParameter(array $arguments)
    {
        $argumentCount = \count($arguments);
        if ($argumentCount < 1) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('issue', 0, 'Eloquent\\Typhoon\\CodeAnalysis\\Issue\\ParameterIssue\\UndocumentedParameter');
        } elseif ($argumentCount > 1) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(1, $arguments[1]);
        }
    }

}
