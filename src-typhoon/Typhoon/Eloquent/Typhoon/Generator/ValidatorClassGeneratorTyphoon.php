<?php
namespace Typhoon\Eloquent\Typhoon\Generator;


class ValidatorClassGeneratorTyphoon extends \Typhoon\Validator
{
    public function validateConstruct(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount > 6))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(6, $arguments[6]));
        }
    }
    public function renderer(array $arguments)
    {
        if ((\count($arguments) > 0))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(0, $arguments[0]));
        }
    }
    public function parser(array $arguments)
    {
        if ((\count($arguments) > 0))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(0, $arguments[0]));
        }
    }
    public function generator(array $arguments)
    {
        if ((\count($arguments) > 0))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(0, $arguments[0]));
        }
    }
    public function classMapper(array $arguments)
    {
        if ((\count($arguments) > 0))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(0, $arguments[0]));
        }
    }
    public function nativeMergeTool(array $arguments)
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
            throw (new \Typhoon\Exception\MissingArgumentException('classDefinition', 0, 'mixed'));
        }
        elseif (($argumentCount > 3))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(3, $arguments[3]));
        }
        if (($argumentCount > 1))
        {
            ($value = $arguments[1]);
            if ((!(\is_string($value) || \is_null($value))))
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
            if ((!(\is_string($value) || \is_null($value))))
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
    public function generateFromSource(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 2))
        {
            if (($argumentCount < 1))
            {
                throw (new \Typhoon\Exception\MissingArgumentException('sourceClassName', 0, 'string'));
            }
            throw (new \Typhoon\Exception\MissingArgumentException('source', 1, 'string'));
        }
        elseif (($argumentCount > 4))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(4, $arguments[4]));
        }
        ($value = $arguments[0]);
        if ((!\is_string($value)))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'sourceClassName',
                0,
                $arguments[0],
                'string'
            ));
        }
        ($value = $arguments[1]);
        if ((!\is_string($value)))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'source',
                1,
                $arguments[1],
                'string'
            ));
        }
        if (($argumentCount > 2))
        {
            ($value = $arguments[2]);
            if ((!(\is_string($value) || \is_null($value))))
            {
                throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                    'namespaceName',
                    2,
                    $arguments[2],
                    'string|null'
                ));
            }
        }
        if (($argumentCount > 3))
        {
            ($value = $arguments[3]);
            if ((!(\is_string($value) || \is_null($value))))
            {
                throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                    'className',
                    3,
                    $arguments[3],
                    'string|null'
                ));
            }
        }
    }
    public function generateFromFile(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 2))
        {
            if (($argumentCount < 1))
            {
                throw (new \Typhoon\Exception\MissingArgumentException('sourceClassName', 0, 'string'));
            }
            throw (new \Typhoon\Exception\MissingArgumentException('path', 1, 'string'));
        }
        elseif (($argumentCount > 4))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(4, $arguments[4]));
        }
        ($value = $arguments[0]);
        if ((!\is_string($value)))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'sourceClassName',
                0,
                $arguments[0],
                'string'
            ));
        }
        ($value = $arguments[1]);
        if ((!\is_string($value)))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'path',
                1,
                $arguments[1],
                'string'
            ));
        }
        if (($argumentCount > 2))
        {
            ($value = $arguments[2]);
            if ((!(\is_string($value) || \is_null($value))))
            {
                throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                    'namespaceName',
                    2,
                    $arguments[2],
                    'string|null'
                ));
            }
        }
        if (($argumentCount > 3))
        {
            ($value = $arguments[3]);
            if ((!(\is_string($value) || \is_null($value))))
            {
                throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                    'className',
                    3,
                    $arguments[3],
                    'string|null'
                ));
            }
        }
    }
    public function generateFromClass(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 1))
        {
            throw (new \Typhoon\Exception\MissingArgumentException('class', 0, 'mixed'));
        }
        elseif (($argumentCount > 3))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(3, $arguments[3]));
        }
        if (($argumentCount > 1))
        {
            ($value = $arguments[1]);
            if ((!(\is_string($value) || \is_null($value))))
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
            if ((!(\is_string($value) || \is_null($value))))
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
            throw (new \Typhoon\Exception\MissingArgumentException('classDefinition', 0, 'mixed'));
        }
        elseif (($argumentCount > 3))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(3, $arguments[3]));
        }
        if (($argumentCount > 1))
        {
            ($value = $arguments[1]);
            if ((!(\is_string($value) || \is_null($value))))
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
            if ((!(\is_string($value) || \is_null($value))))
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
    public function generateMethod(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 2))
        {
            if (($argumentCount < 1))
            {
                throw (new \Typhoon\Exception\MissingArgumentException('method', 0, 'mixed'));
            }
            throw (new \Typhoon\Exception\MissingArgumentException('classDefinition', 1, 'mixed'));
        }
        elseif (($argumentCount > 2))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(2, $arguments[2]));
        }
    }
    public function methods(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 1))
        {
            throw (new \Typhoon\Exception\MissingArgumentException('classDefinition', 0, 'mixed'));
        }
        elseif (($argumentCount > 1))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(1, $arguments[1]));
        }
    }
    public function validatorClassName(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 1))
        {
            throw (new \Typhoon\Exception\MissingArgumentException('classDefinition', 0, 'mixed'));
        }
        elseif (($argumentCount > 3))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(3, $arguments[3]));
        }
        if (($argumentCount > 1))
        {
            ($value = $arguments[1]);
            if ((!(\is_string($value) || \is_null($value))))
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
            if ((!(\is_string($value) || \is_null($value))))
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
    public function validatorMethodName(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 1))
        {
            throw (new \Typhoon\Exception\MissingArgumentException('method', 0, 'mixed'));
        }
        elseif (($argumentCount > 1))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(1, $arguments[1]));
        }
    }
    public function parameterList(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 2))
        {
            if (($argumentCount < 1))
            {
                throw (new \Typhoon\Exception\MissingArgumentException('method', 0, 'mixed'));
            }
            throw (new \Typhoon\Exception\MissingArgumentException('classDefinition', 1, 'mixed'));
        }
        elseif (($argumentCount > 2))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(2, $arguments[2]));
        }
    }
    public function classNameResolver(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 1))
        {
            throw (new \Typhoon\Exception\MissingArgumentException('classDefinition', 0, 'mixed'));
        }
        elseif (($argumentCount > 1))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(1, $arguments[1]));
        }
    }
}
