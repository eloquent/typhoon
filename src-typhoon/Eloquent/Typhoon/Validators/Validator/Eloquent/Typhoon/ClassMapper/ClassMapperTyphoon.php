<?php
namespace Eloquent\Typhoon\Validators\Validator\Eloquent\Typhoon\ClassMapper;


class ClassMapperTyphoon extends \Eloquent\Typhoon\Validators\AbstractValidator
{
    public function validateConstruct(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount > 1))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(1, $arguments[1]));
        }
    }
    public function classesByPath(array $arguments)
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
    public function classesByDirectory(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 1))
        {
            throw (new \Typhoon\Exception\MissingArgumentException('directoryPath', 0, 'string'));
        }
        elseif (($argumentCount > 1))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(1, $arguments[1]));
        }
        ($value = $arguments[0]);
        if ((!\is_string($value)))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'directoryPath',
                0,
                $arguments[0],
                'string'
            ));
        }
    }
    public function classesByFile(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 1))
        {
            throw (new \Typhoon\Exception\MissingArgumentException('filePath', 0, 'string'));
        }
        elseif (($argumentCount > 1))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(1, $arguments[1]));
        }
        ($value = $arguments[0]);
        if ((!\is_string($value)))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'filePath',
                0,
                $arguments[0],
                'string'
            ));
        }
    }
    public function classesBySource(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 1))
        {
            throw (new \Typhoon\Exception\MissingArgumentException('source', 0, 'string'));
        }
        elseif (($argumentCount > 1))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(1, $arguments[1]));
        }
        ($value = $arguments[0]);
        if ((!\is_string($value)))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'source',
                0,
                $arguments[0],
                'string'
            ));
        }
    }
    public function classBySource(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 2))
        {
            if (($argumentCount < 1))
            {
                throw (new \Typhoon\Exception\MissingArgumentException('className', 0, 'string'));
            }
            throw (new \Typhoon\Exception\MissingArgumentException('source', 1, 'string'));
        }
        elseif (($argumentCount > 2))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(2, $arguments[2]));
        }
        ($value = $arguments[0]);
        if ((!\is_string($value)))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'className',
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
    }
    public function parseNamespaceName(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 1))
        {
            throw (new \Typhoon\Exception\MissingArgumentException('tokens', 0, 'array<string|array>'));
        }
        elseif (($argumentCount > 1))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(1, $arguments[1]));
        }
        ($value = $arguments[0]);
        ($check =         function ($value)
                {
                    if ((!\is_array($value)))
                    {
                        return false;
                    }
                    foreach ($value as $key => $subValue)
                    {
                        if ((!(\is_string($subValue) || \is_array($subValue))))
                        {
                            return false;
                        }
                    }
                    return true;
                }
        );
        if ((!$check($arguments[0])))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'tokens',
                0,
                $arguments[0],
                'array<string|array>'
            ));
        }
    }
    public function parseUsedClass(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 1))
        {
            throw (new \Typhoon\Exception\MissingArgumentException('tokens', 0, 'array<string|array>'));
        }
        elseif (($argumentCount > 1))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(1, $arguments[1]));
        }
        ($value = $arguments[0]);
        ($check =         function ($value)
                {
                    if ((!\is_array($value)))
                    {
                        return false;
                    }
                    foreach ($value as $key => $subValue)
                    {
                        if ((!(\is_string($subValue) || \is_array($subValue))))
                        {
                            return false;
                        }
                    }
                    return true;
                }
        );
        if ((!$check($arguments[0])))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'tokens',
                0,
                $arguments[0],
                'array<string|array>'
            ));
        }
    }
    public function parseClassName(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 1))
        {
            throw (new \Typhoon\Exception\MissingArgumentException('tokens', 0, 'array<string|array>'));
        }
        elseif (($argumentCount > 1))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(1, $arguments[1]));
        }
        ($value = $arguments[0]);
        ($check =         function ($value)
                {
                    if ((!\is_array($value)))
                    {
                        return false;
                    }
                    foreach ($value as $key => $subValue)
                    {
                        if ((!(\is_string($subValue) || \is_array($subValue))))
                        {
                            return false;
                        }
                    }
                    return true;
                }
        );
        if ((!$check($arguments[0])))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'tokens',
                0,
                $arguments[0],
                'array<string|array>'
            ));
        }
    }
    public function sourceTokens(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 1))
        {
            throw (new \Typhoon\Exception\MissingArgumentException('source', 0, 'string'));
        }
        elseif (($argumentCount > 1))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(1, $arguments[1]));
        }
        ($value = $arguments[0]);
        if ((!\is_string($value)))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'source',
                0,
                $arguments[0],
                'string'
            ));
        }
    }
    public function fileIterator(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 1))
        {
            throw (new \Typhoon\Exception\MissingArgumentException('directoryPath', 0, 'string'));
        }
        elseif (($argumentCount > 1))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(1, $arguments[1]));
        }
        ($value = $arguments[0]);
        if ((!\is_string($value)))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'directoryPath',
                0,
                $arguments[0],
                'string'
            ));
        }
    }
}
