<?php
namespace Typhoon\Eloquent\Typhoon\Generator\ExceptionGenerator;


class MissingArgumentExceptionGeneratorTyphoon extends \Typhoon\Validator
{
    public function validateConstruct(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount > 1))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(1, $arguments[1]));
        }
    }
    public function renderer(array $arguments)
    {
        if ((\count($arguments) > 0))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(0, $arguments[0]));
        }
    }
    public function generate(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 1))
        {
            throw (new \Typhoon\Exception\MissingArgumentException('configuration', 0, 'mixed'));
        }
        elseif (($argumentCount > 3))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(3, $arguments[3]));
        }
        if (($argumentCount > 1))
        {
            ($value = $arguments[1]);
            if ((!(\is_string($value) || ($value === null))))
            {
                throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                    'namespaceName',
                    1,
                    $arguments[1],
                    'string|null'
                ));
            }
        }
        if (($argumentCount > 2))
        {
            ($value = $arguments[2]);
            if ((!(\is_string($value) || ($value === null))))
            {
                throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                    'className',
                    2,
                    $arguments[2],
                    'string|null'
                ));
            }
        }
    }
    public function generateSyntaxTree(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 1))
        {
            throw (new \Typhoon\Exception\MissingArgumentException('configuration', 0, 'mixed'));
        }
        elseif (($argumentCount > 3))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(3, $arguments[3]));
        }
        if (($argumentCount > 1))
        {
            ($value = $arguments[1]);
            if ((!(\is_string($value) || ($value === null))))
            {
                throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                    'namespaceName',
                    1,
                    $arguments[1],
                    'string|null'
                ));
            }
        }
        if (($argumentCount > 2))
        {
            ($value = $arguments[2]);
            if ((!(\is_string($value) || ($value === null))))
            {
                throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                    'className',
                    2,
                    $arguments[2],
                    'string|null'
                ));
            }
        }
    }
    public function generateConstructor(array $arguments)
    {
        if ((\count($arguments) > 0))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(0, $arguments[0]));
        }
    }
    public function generateParameterNameMethod(array $arguments)
    {
        if ((\count($arguments) > 0))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(0, $arguments[0]));
        }
    }
    public function generateIndexMethod(array $arguments)
    {
        if ((\count($arguments) > 0))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(0, $arguments[0]));
        }
    }
    public function generateExpectedTypeMethod(array $arguments)
    {
        if ((\count($arguments) > 0))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(0, $arguments[0]));
        }
    }
}
