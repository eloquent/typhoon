<?php
namespace Eloquent\Typhoon\TypeCheck\Validator\Eloquent\Typhoon\Configuration;


class ConfigurationReaderTypeCheck extends \Eloquent\Typhoon\TypeCheck\AbstractValidator
{
    public function validateConstruct(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount > 1))
        {
            throw (new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(1, $arguments[1]));
        }
    }
    public function read(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount > 2))
        {
            throw (new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(2, $arguments[2]));
        }
        if (($argumentCount > 0))
        {
            ($value = $arguments[0]);
            if ((!(\is_string($value) || ($value === null))))
            {
                throw (new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                    'path',
                    0,
                    $arguments[0],
                    'string|null'
                ));
            }
        }
        if (($argumentCount > 1))
        {
            ($value = $arguments[1]);
            if ((!\is_bool($value)))
            {
                throw (new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                    'throwOnFailure',
                    1,
                    $arguments[1],
                    'boolean'
                ));
            }
        }
    }
    public function typhoonPath(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 1))
        {
            throw (new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('path', 0, 'string'));
        }
        elseif (($argumentCount > 1))
        {
            throw (new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(1, $arguments[1]));
        }
        ($value = $arguments[0]);
        if ((!\is_string($value)))
        {
            throw (new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                'path',
                0,
                $arguments[0],
                'string'
            ));
        }
    }
    public function composerPath(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 1))
        {
            throw (new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('path', 0, 'string'));
        }
        elseif (($argumentCount > 1))
        {
            throw (new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(1, $arguments[1]));
        }
        ($value = $arguments[0]);
        if ((!\is_string($value)))
        {
            throw (new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                'path',
                0,
                $arguments[0],
                'string'
            ));
        }
    }
    public function readTyphoon(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 1))
        {
            throw (new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('path', 0, 'string'));
        }
        elseif (($argumentCount > 1))
        {
            throw (new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(1, $arguments[1]));
        }
        ($value = $arguments[0]);
        if ((!\is_string($value)))
        {
            throw (new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                'path',
                0,
                $arguments[0],
                'string'
            ));
        }
    }
    public function readComposer(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 1))
        {
            throw (new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('path', 0, 'string'));
        }
        elseif (($argumentCount > 1))
        {
            throw (new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(1, $arguments[1]));
        }
        ($value = $arguments[0]);
        if ((!\is_string($value)))
        {
            throw (new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                'path',
                0,
                $arguments[0],
                'string'
            ));
        }
    }
    public function buildConfiguration(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 1))
        {
            throw (new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('data', 0, 'mixed'));
        }
        elseif (($argumentCount > 1))
        {
            throw (new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(1, $arguments[1]));
        }
    }
    public function loadJSON(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 1))
        {
            throw (new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('path', 0, 'string'));
        }
        elseif (($argumentCount > 1))
        {
            throw (new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(1, $arguments[1]));
        }
        ($value = $arguments[0]);
        if ((!\is_string($value)))
        {
            throw (new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                'path',
                0,
                $arguments[0],
                'string'
            ));
        }
    }
    public function load(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 1))
        {
            throw (new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('path', 0, 'string'));
        }
        elseif (($argumentCount > 1))
        {
            throw (new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(1, $arguments[1]));
        }
        ($value = $arguments[0]);
        if ((!\is_string($value)))
        {
            throw (new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                'path',
                0,
                $arguments[0],
                'string'
            ));
        }
    }
    public function validateData(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 1))
        {
            throw (new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('data', 0, 'mixed'));
        }
        elseif (($argumentCount > 1))
        {
            throw (new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(1, $arguments[1]));
        }
    }
    public function pathIsDescandantOrEqual(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 3))
        {
            if (($argumentCount < 1))
            {
                throw (new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('workingPath', 0, 'string'));
            }
            if (($argumentCount < 2))
            {
                throw (new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('ancestor', 1, 'string'));
            }
            throw (new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('descendant', 2, 'string'));
        }
        elseif (($argumentCount > 3))
        {
            throw (new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(3, $arguments[3]));
        }
        ($value = $arguments[0]);
        if ((!\is_string($value)))
        {
            throw (new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                'workingPath',
                0,
                $arguments[0],
                'string'
            ));
        }
        ($value = $arguments[1]);
        if ((!\is_string($value)))
        {
            throw (new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                'ancestor',
                1,
                $arguments[1],
                'string'
            ));
        }
        ($value = $arguments[2]);
        if ((!\is_string($value)))
        {
            throw (new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                'descendant',
                2,
                $arguments[2],
                'string'
            ));
        }
    }
    public function normalizePath(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 2))
        {
            if (($argumentCount < 1))
            {
                throw (new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('workingPath', 0, 'string'));
            }
            throw (new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('path', 1, 'string'));
        }
        elseif (($argumentCount > 2))
        {
            throw (new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(2, $arguments[2]));
        }
        ($value = $arguments[0]);
        if ((!\is_string($value)))
        {
            throw (new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                'workingPath',
                0,
                $arguments[0],
                'string'
            ));
        }
        ($value = $arguments[1]);
        if ((!\is_string($value)))
        {
            throw (new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                'path',
                1,
                $arguments[1],
                'string'
            ));
        }
    }
}
