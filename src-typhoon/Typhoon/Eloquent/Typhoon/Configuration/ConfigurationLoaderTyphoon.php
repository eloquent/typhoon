<?php
namespace Typhoon\Eloquent\Typhoon\Configuration;


class ConfigurationLoaderTyphoon extends \Typhoon\Validator
{
    public function validateConstruct(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount > 2))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(2, $arguments[2]));
        }
    }
    public function validator(array $arguments)
    {
        if ((\count($arguments) > 0))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(0, $arguments[0]));
        }
    }
    public function load(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount > 1))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(1, $arguments[1]));
        }
        if (($argumentCount > 0))
        {
            ($value = $arguments[0]);
            if ((!(\is_string($value) || ($value === null))))
            {
                throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                    'workingPath',
                    0,
                    $arguments[0],
                    'string|null'
                ));
            }
        }
    }
    public function loadStandalone(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 1))
        {
            throw (new \Typhoon\Exception\MissingArgumentException('path', 0, 'string'));
        }
        elseif (($argumentCount > 2))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(2, $arguments[2]));
        }
        ($value = $arguments[0]);
        if ((!\is_string($value)))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'path',
                0,
                $arguments[0],
                'string'
            ));
        }
        if (($argumentCount > 1))
        {
            ($value = $arguments[1]);
            if ((!(\is_string($value) || ($value === null))))
            {
                throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                    'workingPath',
                    1,
                    $arguments[1],
                    'string|null'
                ));
            }
        }
    }
    public function loadComposer(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 1))
        {
            throw (new \Typhoon\Exception\MissingArgumentException('path', 0, 'string'));
        }
        elseif (($argumentCount > 2))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(2, $arguments[2]));
        }
        ($value = $arguments[0]);
        if ((!\is_string($value)))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'path',
                0,
                $arguments[0],
                'string'
            ));
        }
        if (($argumentCount > 1))
        {
            ($value = $arguments[1]);
            if ((!(\is_string($value) || ($value === null))))
            {
                throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                    'workingPath',
                    1,
                    $arguments[1],
                    'string|null'
                ));
            }
        }
    }
    public function loadJSONFile(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 1))
        {
            throw (new \Typhoon\Exception\MissingArgumentException('path', 0, 'string'));
        }
        elseif (($argumentCount > 1))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(1, $arguments[1]));
        }
        ($value = $arguments[0]);
        if ((!\is_string($value)))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'path',
                0,
                $arguments[0],
                'string'
            ));
        }
    }
    public function build(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 1))
        {
            throw (new \Typhoon\Exception\MissingArgumentException('data', 0, 'mixed'));
        }
        elseif (($argumentCount > 2))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(2, $arguments[2]));
        }
        if (($argumentCount > 1))
        {
            ($value = $arguments[1]);
            if ((!(\is_string($value) || ($value === null))))
            {
                throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                    'workingPath',
                    1,
                    $arguments[1],
                    'string|null'
                ));
            }
        }
    }
    public function defaultLoaderPaths(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 1))
        {
            throw (new \Typhoon\Exception\MissingArgumentException('workingPath', 0, 'string'));
        }
        elseif (($argumentCount > 1))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(1, $arguments[1]));
        }
        ($value = $arguments[0]);
        if ((!\is_string($value)))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'workingPath',
                0,
                $arguments[0],
                'string'
            ));
        }
    }
    public function objectHasProperty(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 2))
        {
            if (($argumentCount < 1))
            {
                throw (new \Typhoon\Exception\MissingArgumentException('object', 0, 'mixed'));
            }
            throw (new \Typhoon\Exception\MissingArgumentException('property', 1, 'string'));
        }
        elseif (($argumentCount > 2))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(2, $arguments[2]));
        }
        ($value = $arguments[1]);
        if ((!\is_string($value)))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'property',
                1,
                $arguments[1],
                'string'
            ));
        }
    }
}
