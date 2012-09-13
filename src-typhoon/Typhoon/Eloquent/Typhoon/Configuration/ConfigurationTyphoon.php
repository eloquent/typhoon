<?php
namespace Typhoon\Eloquent\Typhoon\Configuration;


class ConfigurationTyphoon extends \Typhoon\Validator
{
    public function validateConstruct(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 4))
        {
            if (($argumentCount < 1))
            {
                throw (new \Typhoon\Exception\MissingArgumentException('outputPath', 0, 'string'));
            }
            if (($argumentCount < 2))
            {
                throw (new \Typhoon\Exception\MissingArgumentException('sourcePaths', 1, 'array<string>'));
            }
            if (($argumentCount < 3))
            {
                throw (new \Typhoon\Exception\MissingArgumentException('loaderPaths', 2, 'array<string>'));
            }
            throw (new \Typhoon\Exception\MissingArgumentException('useNativeCallable', 3, 'boolean'));
        }
        elseif (($argumentCount > 4))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(4, $arguments[4]));
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
        ($value = $arguments[2]);
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
        if ((!$check($arguments[2])))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'loaderPaths',
                2,
                $arguments[2],
                'array<string>'
            ));
        }
        ($value = $arguments[3]);
        if ((!\is_bool($value)))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'useNativeCallable',
                3,
                $arguments[3],
                'boolean'
            ));
        }
    }
    public function outputPath(array $arguments)
    {
        if ((\count($arguments) > 0))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(0, $arguments[0]));
        }
    }
    public function sourcePaths(array $arguments)
    {
        if ((\count($arguments) > 0))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(0, $arguments[0]));
        }
    }
    public function loaderPaths(array $arguments)
    {
        if ((\count($arguments) > 0))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(0, $arguments[0]));
        }
    }
    public function useNativeCallable(array $arguments)
    {
        if ((\count($arguments) > 0))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(0, $arguments[0]));
        }
    }
}
