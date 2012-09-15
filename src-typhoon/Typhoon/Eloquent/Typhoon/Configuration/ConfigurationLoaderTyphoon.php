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
    public function buildConfiguration(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 2))
        {
            if (($argumentCount < 1))
            {
                throw (new \Typhoon\Exception\MissingArgumentException('outputPath', 0, 'string'));
            }
            throw (new \Typhoon\Exception\MissingArgumentException('sourcePaths', 1, 'array<string>'));
        }
        elseif (($argumentCount > 5))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(5, $arguments[5]));
        }
        ($value = $arguments[0]);
        if ((!\is_string($value)))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'outputPath',
                0,
                $arguments[0],
                'string'
            ));
        }
        ($value = $arguments[1]);
        ($check =         function ($value)
                {
                    if ((!\is_array($value)))
                    {
                        return false;
                    }
                    foreach ($value as $key => $subValue)
                    {
                        if ((!\is_string($subValue)))
                        {
                            return false;
                        }
                    }
                    return true;
                }
        );
        if ((!$check($arguments[1])))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'sourcePaths',
                1,
                $arguments[1],
                'array<string>'
            ));
        }
        if (($argumentCount > 2))
        {
            ($value = $arguments[2]);
            ($check =             function ($value)
                        {
                            ($check =                 function ($value)
                                            {
                                                if ((!\is_array($value)))
                                                {
                                                    return false;
                                                }
                                                foreach ($value as $key => $subValue)
                                                {
                                                    if ((!\is_string($subValue)))
                                                    {
                                                        return false;
                                                    }
                                                }
                                                return true;
                                            }
                            );
                            if (                function ($value)
                                            {
                                                if ((!\is_array($value)))
                                                {
                                                    return false;
                                                }
                                                foreach ($value as $key => $subValue)
                                                {
                                                    if ((!\is_string($subValue)))
                                                    {
                                                        return false;
                                                    }
                                                }
                                                return true;
                                            }
                            )
                            {
                                return true;
                            }
                            return ($value === null);
                        }
            );
            if ((!$check($arguments[2])))
            {
                throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                    'loaderPaths',
                    2,
                    $arguments[2],
                    'array<string>|null'
                ));
            }
        }
        if (($argumentCount > 3))
        {
            ($value = $arguments[3]);
            if ((!(\is_bool($value) || ($value === null))))
            {
                throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                    'useNativeCallable',
                    3,
                    $arguments[3],
                    'boolean|null'
                ));
            }
        }
        if (($argumentCount > 4))
        {
            ($value = $arguments[4]);
            if ((!(\is_string($value) || ($value === null))))
            {
                throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                    'workingPath',
                    4,
                    $arguments[4],
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
    public function buildFromData(array $arguments)
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
    public function workingPath(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 1))
        {
            throw (new \Typhoon\Exception\MissingArgumentException('workingPath', 0, 'string|null'));
        }
        elseif (($argumentCount > 1))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(1, $arguments[1]));
        }
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
