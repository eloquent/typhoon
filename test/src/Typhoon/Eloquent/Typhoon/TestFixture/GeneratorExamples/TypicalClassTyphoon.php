<?php
namespace Typhoon\Eloquent\Typhoon\TestFixture\GeneratorExamples;


class TypicalClassTyphoon extends \Typhoon\Validator
{
    public function validateConstruct(array $arguments)
    {
        if ((\count($arguments) > 0))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(0, $arguments[0]));
        }
    }
    public function validateToString(array $arguments)
    {
        if ((\count($arguments) > 0))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(0, $arguments[0]));
        }
    }
    public function undocumentedMethod(array $arguments)
    {
        if ((\count($arguments) > 0))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(0, $arguments[0]));
        }
    }
    public function simpleTypes(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 6))
        {
            if (($argumentCount < 1))
            {
                throw (new \Typhoon\Exception\MissingArgumentException('boolean', 0, 'boolean'));
            }
            if (($argumentCount < 2))
            {
                throw (new \Typhoon\Exception\MissingArgumentException('float', 1, 'float'));
            }
            if (($argumentCount < 3))
            {
                throw (new \Typhoon\Exception\MissingArgumentException('integer', 2, 'integer'));
            }
            if (($argumentCount < 4))
            {
                throw (new \Typhoon\Exception\MissingArgumentException('mixed', 3, 'mixed'));
            }
            if (($argumentCount < 5))
            {
                throw (new \Typhoon\Exception\MissingArgumentException('numeric', 4, 'numeric'));
            }
            throw (new \Typhoon\Exception\MissingArgumentException('string', 5, 'string'));
        }
        elseif (($argumentCount > 6))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(6, $arguments[6]));
        }
        ($value = $arguments[0]);
        if ((!\is_bool($value)))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'boolean',
                0,
                $arguments[0],
                'boolean'
            ));
        }
        ($value = $arguments[1]);
        if ((!\is_float($value)))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'float',
                1,
                $arguments[1],
                'float'
            ));
        }
        ($value = $arguments[2]);
        if ((!\is_int($value)))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'integer',
                2,
                $arguments[2],
                'integer'
            ));
        }
        ($value = $arguments[4]);
        if ((!\is_numeric($value)))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'numeric',
                4,
                $arguments[4],
                'numeric'
            ));
        }
        ($value = $arguments[5]);
        if ((!\is_string($value)))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'string',
                5,
                $arguments[5],
                'string'
            ));
        }
    }
    public function objectType(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 3))
        {
            if (($argumentCount < 1))
            {
                throw (new \Typhoon\Exception\MissingArgumentException('foo', 0, 'object'));
            }
            if (($argumentCount < 2))
            {
                throw (new \Typhoon\Exception\MissingArgumentException('bar', 1, 'mixed'));
            }
            throw (new \Typhoon\Exception\MissingArgumentException('baz', 2, 'array<stdClass>'));
        }
        elseif (($argumentCount > 3))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(3, $arguments[3]));
        }
        ($value = $arguments[0]);
        if ((!\is_object($value)))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'foo',
                0,
                $arguments[0],
                'object'
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
                        if ((!($subValue instanceof \stdClass)))
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
                'baz',
                2,
                $arguments[2],
                'array<stdClass>'
            ));
        }
    }
    public function resourceType(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 2))
        {
            if (($argumentCount < 1))
            {
                throw (new \Typhoon\Exception\MissingArgumentException('foo', 0, 'resource'));
            }
            throw (new \Typhoon\Exception\MissingArgumentException('bar', 1, 'resource {ofType: \'stream\'}'));
        }
        elseif (($argumentCount > 2))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(2, $arguments[2]));
        }
        ($value = $arguments[0]);
        if ((!\is_resource($value)))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'foo',
                0,
                $arguments[0],
                'resource'
            ));
        }
        ($value = $arguments[1]);
        if ((!(\is_resource($value) && (\get_resource_type($value) === 'stream'))))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'bar',
                1,
                $arguments[1],
                'resource {ofType: \'stream\'}'
            ));
        }
    }
    public function streamType(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 7))
        {
            if (($argumentCount < 1))
            {
                throw (new \Typhoon\Exception\MissingArgumentException('foo', 0, 'stream'));
            }
            if (($argumentCount < 2))
            {
                throw (new \Typhoon\Exception\MissingArgumentException('bar', 1, 'stream {readable: true}'));
            }
            if (($argumentCount < 3))
            {
                throw (new \Typhoon\Exception\MissingArgumentException('baz', 2, 'stream {readable: false}'));
            }
            if (($argumentCount < 4))
            {
                throw (new \Typhoon\Exception\MissingArgumentException('qux', 3, 'stream {writable: true}'));
            }
            if (($argumentCount < 5))
            {
                throw (new \Typhoon\Exception\MissingArgumentException('doom', 4, 'stream {writable: false}'));
            }
            if (($argumentCount < 6))
            {
                throw (new \Typhoon\Exception\MissingArgumentException('splat', 5, 'stream {readable: true, writable: true}'));
            }
            throw (new \Typhoon\Exception\MissingArgumentException('ping', 6, 'stream {readable: false, writable: true}'));
        }
        elseif (($argumentCount > 7))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(7, $arguments[7]));
        }
        ($value = $arguments[0]);
        if ((!(\is_resource($value) && (\get_resource_type($value) === 'stream'))))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'foo',
                0,
                $arguments[0],
                'stream'
            ));
        }
        ($value = $arguments[1]);
        ($check =         function ($value)
                {
                    if ((!(\is_resource($value) && (\get_resource_type($value) === 'stream'))))
                    {
                        return false;
                    }
                    ($streamMetaData = stream_get_meta_data($value));
                    return (\strpbrk($streamMetaData['mode'], 'r+') !== false);
                }
        );
        if ((!$check($arguments[1])))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'bar',
                1,
                $arguments[1],
                'stream {readable: true}'
            ));
        }
        ($value = $arguments[2]);
        ($check =         function ($value)
                {
                    if ((!(\is_resource($value) && (\get_resource_type($value) === 'stream'))))
                    {
                        return false;
                    }
                    ($streamMetaData = stream_get_meta_data($value));
                    return (!(\strpbrk($streamMetaData['mode'], 'r+') !== false));
                }
        );
        if ((!$check($arguments[2])))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'baz',
                2,
                $arguments[2],
                'stream {readable: false}'
            ));
        }
        ($value = $arguments[3]);
        ($check =         function ($value)
                {
                    if ((!(\is_resource($value) && (\get_resource_type($value) === 'stream'))))
                    {
                        return false;
                    }
                    ($streamMetaData = stream_get_meta_data($value));
                    return (\strpbrk($streamMetaData['mode'], 'waxc+') !== false);
                }
        );
        if ((!$check($arguments[3])))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'qux',
                3,
                $arguments[3],
                'stream {writable: true}'
            ));
        }
        ($value = $arguments[4]);
        ($check =         function ($value)
                {
                    if ((!(\is_resource($value) && (\get_resource_type($value) === 'stream'))))
                    {
                        return false;
                    }
                    ($streamMetaData = stream_get_meta_data($value));
                    return (!(\strpbrk($streamMetaData['mode'], 'waxc+') !== false));
                }
        );
        if ((!$check($arguments[4])))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'doom',
                4,
                $arguments[4],
                'stream {writable: false}'
            ));
        }
        ($value = $arguments[5]);
        ($check =         function ($value)
                {
                    if ((!(\is_resource($value) && (\get_resource_type($value) === 'stream'))))
                    {
                        return false;
                    }
                    ($streamMetaData = stream_get_meta_data($value));
                    if ((!(\strpbrk($streamMetaData['mode'], 'r+') !== false)))
                    {
                        return false;
                    }
                    return (\strpbrk($streamMetaData['mode'], 'waxc+') !== false);
                }
        );
        if ((!$check($arguments[5])))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'splat',
                5,
                $arguments[5],
                'stream {readable: true, writable: true}'
            ));
        }
        ($value = $arguments[6]);
        ($check =         function ($value)
                {
                    if ((!(\is_resource($value) && (\get_resource_type($value) === 'stream'))))
                    {
                        return false;
                    }
                    ($streamMetaData = stream_get_meta_data($value));
                    if ((\strpbrk($streamMetaData['mode'], 'r+') !== false))
                    {
                        return false;
                    }
                    return (\strpbrk($streamMetaData['mode'], 'waxc+') !== false);
                }
        );
        if ((!$check($arguments[6])))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'ping',
                6,
                $arguments[6],
                'stream {readable: false, writable: true}'
            ));
        }
    }
    public function stringableType(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 1))
        {
            throw (new \Typhoon\Exception\MissingArgumentException('foo', 0, 'stringable'));
        }
        elseif (($argumentCount > 1))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(1, $arguments[1]));
        }
        ($value = $arguments[0]);
        ($check =         function ($value)
                {
                    if ((\is_string($value) || \is_int($value) || \is_float($value)))
                    {
                        return true;
                    }
                    if ((!\is_object($value)))
                    {
                        return false;
                    }
                    ($reflector = (new \ReflectionObject($value)));
                    return $reflector->hasMethod('__toString');
                }
        );
        if ((!$check($arguments[0])))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'foo',
                0,
                $arguments[0],
                'stringable'
            ));
        }
    }
    public function callableType(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 1))
        {
            throw (new \Typhoon\Exception\MissingArgumentException('foo', 0, 'array<callable>'));
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
                        if ((!\is_callable($subValue)))
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
                'foo',
                0,
                $arguments[0],
                'array<callable>'
            ));
        }
    }
    public function nullType(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 1))
        {
            throw (new \Typhoon\Exception\MissingArgumentException('foo', 0, 'array<null>'));
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
                        if ((!\is_null($subValue)))
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
                'foo',
                0,
                $arguments[0],
                'array<null>'
            ));
        }
    }
    public function andType(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 4))
        {
            if (($argumentCount < 1))
            {
                throw (new \Typhoon\Exception\MissingArgumentException('foo', 0, 'stdClass+Iterator'));
            }
            if (($argumentCount < 2))
            {
                throw (new \Typhoon\Exception\MissingArgumentException('bar', 1, 'object+stringable'));
            }
            if (($argumentCount < 3))
            {
                throw (new \Typhoon\Exception\MissingArgumentException('baz', 2, 'stringable+object'));
            }
            throw (new \Typhoon\Exception\MissingArgumentException('qux', 3, 'mixed+mixed'));
        }
        elseif (($argumentCount > 4))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(4, $arguments[4]));
        }
        ($value = $arguments[0]);
        if ((!(($value instanceof \stdClass) && ($value instanceof \Iterator))))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'foo',
                0,
                $arguments[0],
                'stdClass+Iterator'
            ));
        }
        ($value = $arguments[1]);
        ($check =         function ($value)
                {
                    if ((!\is_object($value)))
                    {
                        return false;
                    }
                    if ((\is_string($value) || \is_int($value) || \is_float($value)))
                    {
                        return true;
                    }
                    if ((!\is_object($value)))
                    {
                        return false;
                    }
                    ($reflector = (new \ReflectionObject($value)));
                    return $reflector->hasMethod('__toString');
                }
        );
        if ((!$check($arguments[1])))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'bar',
                1,
                $arguments[1],
                'object+stringable'
            ));
        }
        ($value = $arguments[2]);
        ($check =         function ($value)
                {
                    ($check =             function ($value)
                                {
                                    if ((\is_string($value) || \is_int($value) || \is_float($value)))
                                    {
                                        return true;
                                    }
                                    if ((!\is_object($value)))
                                    {
                                        return false;
                                    }
                                    ($reflector = (new \ReflectionObject($value)));
                                    return $reflector->hasMethod('__toString');
                                }
                    );
                    if ((!            function ($value)
                                {
                                    if ((\is_string($value) || \is_int($value) || \is_float($value)))
                                    {
                                        return true;
                                    }
                                    if ((!\is_object($value)))
                                    {
                                        return false;
                                    }
                                    ($reflector = (new \ReflectionObject($value)));
                                    return $reflector->hasMethod('__toString');
                                }
                    ))
                    {
                        return false;
                    }
                    return \is_object($value);
                }
        );
        if ((!$check($arguments[2])))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'baz',
                2,
                $arguments[2],
                'stringable+object'
            ));
        }
    }
    public function orType(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 4))
        {
            if (($argumentCount < 1))
            {
                throw (new \Typhoon\Exception\MissingArgumentException('foo', 0, 'integer|string'));
            }
            if (($argumentCount < 2))
            {
                throw (new \Typhoon\Exception\MissingArgumentException('bar', 1, 'integer|stringable'));
            }
            if (($argumentCount < 3))
            {
                throw (new \Typhoon\Exception\MissingArgumentException('baz', 2, 'stringable|integer'));
            }
            throw (new \Typhoon\Exception\MissingArgumentException('qux', 3, 'mixed|mixed'));
        }
        elseif (($argumentCount > 4))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(4, $arguments[4]));
        }
        ($value = $arguments[0]);
        if ((!(\is_int($value) || \is_string($value))))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'foo',
                0,
                $arguments[0],
                'integer|string'
            ));
        }
        ($value = $arguments[1]);
        ($check =         function ($value)
                {
                    if (\is_int($value))
                    {
                        return true;
                    }
                    if ((\is_string($value) || \is_int($value) || \is_float($value)))
                    {
                        return true;
                    }
                    if ((!\is_object($value)))
                    {
                        return false;
                    }
                    ($reflector = (new \ReflectionObject($value)));
                    return $reflector->hasMethod('__toString');
                }
        );
        if ((!$check($arguments[1])))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'bar',
                1,
                $arguments[1],
                'integer|stringable'
            ));
        }
        ($value = $arguments[2]);
        ($check =         function ($value)
                {
                    ($check =             function ($value)
                                {
                                    if ((\is_string($value) || \is_int($value) || \is_float($value)))
                                    {
                                        return true;
                                    }
                                    if ((!\is_object($value)))
                                    {
                                        return false;
                                    }
                                    ($reflector = (new \ReflectionObject($value)));
                                    return $reflector->hasMethod('__toString');
                                }
                    );
                    if (            function ($value)
                                {
                                    if ((\is_string($value) || \is_int($value) || \is_float($value)))
                                    {
                                        return true;
                                    }
                                    if ((!\is_object($value)))
                                    {
                                        return false;
                                    }
                                    ($reflector = (new \ReflectionObject($value)));
                                    return $reflector->hasMethod('__toString');
                                }
                    )
                    {
                        return true;
                    }
                    return \is_int($value);
                }
        );
        if ((!$check($arguments[2])))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'baz',
                2,
                $arguments[2],
                'stringable|integer'
            ));
        }
    }
    public function tupleType(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 2))
        {
            if (($argumentCount < 1))
            {
                throw (new \Typhoon\Exception\MissingArgumentException('foo', 0, 'tuple<integer, string>'));
            }
            throw (new \Typhoon\Exception\MissingArgumentException('bar', 1, 'tuple<integer, stringable, stringable>'));
        }
        elseif (($argumentCount > 2))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(2, $arguments[2]));
        }
        ($value = $arguments[0]);
        if ((!(\is_array($value) && (\array_keys($value) === \range(0, 1)) && \is_int($value[0]) && \is_string($value[1]))))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'foo',
                0,
                $arguments[0],
                'tuple<integer, string>'
            ));
        }
        ($value = $arguments[1]);
        ($check =         function ($value)
                {
                    if ((!(\is_array($value) && (\array_keys($value) === \range(0, 2)) && \is_int($value[0]))))
                    {
                        return false;
                    }
                    ($check =             function ($value)
                                {
                                    if ((\is_string($value) || \is_int($value) || \is_float($value)))
                                    {
                                        return true;
                                    }
                                    if ((!\is_object($value)))
                                    {
                                        return false;
                                    }
                                    ($reflector = (new \ReflectionObject($value)));
                                    return $reflector->hasMethod('__toString');
                                }
                    );
                    if ((!$check($value[1])))
                    {
                        return false;
                    }
                    ($check =             function ($value)
                                {
                                    if ((\is_string($value) || \is_int($value) || \is_float($value)))
                                    {
                                        return true;
                                    }
                                    if ((!\is_object($value)))
                                    {
                                        return false;
                                    }
                                    ($reflector = (new \ReflectionObject($value)));
                                    return $reflector->hasMethod('__toString');
                                }
                    );
                    return $check($value[2]);
                }
        );
        if ((!$check($arguments[1])))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'bar',
                1,
                $arguments[1],
                'tuple<integer, stringable, stringable>'
            ));
        }
    }
    public function traversableArray(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 5))
        {
            if (($argumentCount < 1))
            {
                throw (new \Typhoon\Exception\MissingArgumentException('foo', 0, 'mixed'));
            }
            if (($argumentCount < 2))
            {
                throw (new \Typhoon\Exception\MissingArgumentException('bar', 1, 'array<array>'));
            }
            if (($argumentCount < 3))
            {
                throw (new \Typhoon\Exception\MissingArgumentException('baz', 2, 'array<integer, string>'));
            }
            if (($argumentCount < 4))
            {
                throw (new \Typhoon\Exception\MissingArgumentException('qux', 3, 'array<integer, stringable>'));
            }
            throw (new \Typhoon\Exception\MissingArgumentException('doom', 4, 'array<stringable, integer>'));
        }
        elseif (($argumentCount > 5))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(5, $arguments[5]));
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
                        if ((!\is_array($subValue)))
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
                'bar',
                1,
                $arguments[1],
                'array<array>'
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
                        if ((!\is_int($key)))
                        {
                            return false;
                        }
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
                'baz',
                2,
                $arguments[2],
                'array<integer, string>'
            ));
        }
        ($value = $arguments[3]);
        ($check =         function ($value)
                {
                    if ((!\is_array($value)))
                    {
                        return false;
                    }
                    ($valueCheck =             function ($subValue)
                                {
                                    if ((\is_string($subValue) || \is_int($subValue) || \is_float($subValue)))
                                    {
                                        return true;
                                    }
                                    if ((!\is_object($subValue)))
                                    {
                                        return false;
                                    }
                                    ($reflector = (new \ReflectionObject($subValue)));
                                    return $reflector->hasMethod('__toString');
                                }
                    );
                    foreach ($value as $key => $subValue)
                    {
                        if ((!\is_int($key)))
                        {
                            return false;
                        }
                        if ((!$valueCheck($subValue)))
                        {
                            return false;
                        }
                    }
                    return true;
                }
        );
        if ((!$check($arguments[3])))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'qux',
                3,
                $arguments[3],
                'array<integer, stringable>'
            ));
        }
        ($value = $arguments[4]);
        ($check =         function ($value)
                {
                    if ((!\is_array($value)))
                    {
                        return false;
                    }
                    ($keyCheck =             function ($key)
                                {
                                    if ((\is_string($key) || \is_int($key) || \is_float($key)))
                                    {
                                        return true;
                                    }
                                    if ((!\is_object($key)))
                                    {
                                        return false;
                                    }
                                    ($reflector = (new \ReflectionObject($key)));
                                    return $reflector->hasMethod('__toString');
                                }
                    );
                    foreach ($value as $key => $subValue)
                    {
                        if ((!$keyCheck($key)))
                        {
                            return false;
                        }
                        if ((!\is_int($subValue)))
                        {
                            return false;
                        }
                    }
                    return true;
                }
        );
        if ((!$check($arguments[4])))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'doom',
                4,
                $arguments[4],
                'array<stringable, integer>'
            ));
        }
    }
    public function traversableObject(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 4))
        {
            if (($argumentCount < 1))
            {
                throw (new \Typhoon\Exception\MissingArgumentException('foo', 0, 'Iterator<string>'));
            }
            if (($argumentCount < 2))
            {
                throw (new \Typhoon\Exception\MissingArgumentException('bar', 1, 'Iterator<integer, string>'));
            }
            if (($argumentCount < 3))
            {
                throw (new \Typhoon\Exception\MissingArgumentException('baz', 2, 'Iterator<integer, stringable>'));
            }
            throw (new \Typhoon\Exception\MissingArgumentException('qux', 3, 'Iterator<stringable, integer>'));
        }
        elseif (($argumentCount > 4))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(4, $arguments[4]));
        }
        ($value = $arguments[0]);
        ($check =         function ($value)
                {
                    if ((!($value instanceof \Traversable)))
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
        if ((!$check($arguments[0])))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'foo',
                0,
                $arguments[0],
                'Iterator<string>'
            ));
        }
        ($value = $arguments[1]);
        ($check =         function ($value)
                {
                    if ((!($value instanceof \Traversable)))
                    {
                        return false;
                    }
                    foreach ($value as $key => $subValue)
                    {
                        if ((!\is_int($key)))
                        {
                            return false;
                        }
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
                'bar',
                1,
                $arguments[1],
                'Iterator<integer, string>'
            ));
        }
        ($value = $arguments[2]);
        ($check =         function ($value)
                {
                    if ((!($value instanceof \Traversable)))
                    {
                        return false;
                    }
                    ($valueCheck =             function ($subValue)
                                {
                                    if ((\is_string($subValue) || \is_int($subValue) || \is_float($subValue)))
                                    {
                                        return true;
                                    }
                                    if ((!\is_object($subValue)))
                                    {
                                        return false;
                                    }
                                    ($reflector = (new \ReflectionObject($subValue)));
                                    return $reflector->hasMethod('__toString');
                                }
                    );
                    foreach ($value as $key => $subValue)
                    {
                        if ((!\is_int($key)))
                        {
                            return false;
                        }
                        if ((!$valueCheck($subValue)))
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
                'baz',
                2,
                $arguments[2],
                'Iterator<integer, stringable>'
            ));
        }
        ($value = $arguments[3]);
        ($check =         function ($value)
                {
                    if ((!($value instanceof \Traversable)))
                    {
                        return false;
                    }
                    ($keyCheck =             function ($key)
                                {
                                    if ((\is_string($key) || \is_int($key) || \is_float($key)))
                                    {
                                        return true;
                                    }
                                    if ((!\is_object($key)))
                                    {
                                        return false;
                                    }
                                    ($reflector = (new \ReflectionObject($key)));
                                    return $reflector->hasMethod('__toString');
                                }
                    );
                    foreach ($value as $key => $subValue)
                    {
                        if ((!$keyCheck($key)))
                        {
                            return false;
                        }
                        if ((!\is_int($subValue)))
                        {
                            return false;
                        }
                    }
                    return true;
                }
        );
        if ((!$check($arguments[3])))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'qux',
                3,
                $arguments[3],
                'Iterator<stringable, integer>'
            ));
        }
    }
    public function traversableMixed(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 4))
        {
            if (($argumentCount < 1))
            {
                throw (new \Typhoon\Exception\MissingArgumentException('foo', 0, 'mixed<string>'));
            }
            if (($argumentCount < 2))
            {
                throw (new \Typhoon\Exception\MissingArgumentException('bar', 1, 'mixed<integer, string>'));
            }
            if (($argumentCount < 3))
            {
                throw (new \Typhoon\Exception\MissingArgumentException('baz', 2, 'mixed<integer, stringable>'));
            }
            throw (new \Typhoon\Exception\MissingArgumentException('qux', 3, 'mixed<stringable, integer>'));
        }
        elseif (($argumentCount > 4))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(4, $arguments[4]));
        }
        ($value = $arguments[0]);
        ($check =         function ($value)
                {
                    if (((!\is_array($value)) && (!($value instanceof \Traversable))))
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
        if ((!$check($arguments[0])))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'foo',
                0,
                $arguments[0],
                'mixed<string>'
            ));
        }
        ($value = $arguments[1]);
        ($check =         function ($value)
                {
                    if (((!\is_array($value)) && (!($value instanceof \Traversable))))
                    {
                        return false;
                    }
                    foreach ($value as $key => $subValue)
                    {
                        if ((!\is_int($key)))
                        {
                            return false;
                        }
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
                'bar',
                1,
                $arguments[1],
                'mixed<integer, string>'
            ));
        }
        ($value = $arguments[2]);
        ($check =         function ($value)
                {
                    if (((!\is_array($value)) && (!($value instanceof \Traversable))))
                    {
                        return false;
                    }
                    ($valueCheck =             function ($subValue)
                                {
                                    if ((\is_string($subValue) || \is_int($subValue) || \is_float($subValue)))
                                    {
                                        return true;
                                    }
                                    if ((!\is_object($subValue)))
                                    {
                                        return false;
                                    }
                                    ($reflector = (new \ReflectionObject($subValue)));
                                    return $reflector->hasMethod('__toString');
                                }
                    );
                    foreach ($value as $key => $subValue)
                    {
                        if ((!\is_int($key)))
                        {
                            return false;
                        }
                        if ((!$valueCheck($subValue)))
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
                'baz',
                2,
                $arguments[2],
                'mixed<integer, stringable>'
            ));
        }
        ($value = $arguments[3]);
        ($check =         function ($value)
                {
                    if (((!\is_array($value)) && (!($value instanceof \Traversable))))
                    {
                        return false;
                    }
                    ($keyCheck =             function ($key)
                                {
                                    if ((\is_string($key) || \is_int($key) || \is_float($key)))
                                    {
                                        return true;
                                    }
                                    if ((!\is_object($key)))
                                    {
                                        return false;
                                    }
                                    ($reflector = (new \ReflectionObject($key)));
                                    return $reflector->hasMethod('__toString');
                                }
                    );
                    foreach ($value as $key => $subValue)
                    {
                        if ((!$keyCheck($key)))
                        {
                            return false;
                        }
                        if ((!\is_int($subValue)))
                        {
                            return false;
                        }
                    }
                    return true;
                }
        );
        if ((!$check($arguments[3])))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'qux',
                3,
                $arguments[3],
                'mixed<stringable, integer>'
            ));
        }
    }
    public function optionalParameter(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 1))
        {
            throw (new \Typhoon\Exception\MissingArgumentException('foo', 0, 'string'));
        }
        elseif (($argumentCount > 2))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(2, $arguments[2]));
        }
        ($value = $arguments[0]);
        if ((!\is_string($value)))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'foo',
                0,
                $arguments[0],
                'string'
            ));
        }
        if (($argumentCount > 1))
        {
            ($value = $arguments[1]);
            if ((!\is_string($value)))
            {
                throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                    'bar',
                    1,
                    $arguments[1],
                    'string'
                ));
            }
        }
    }
    public function onlyOptional(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount > 1))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(1, $arguments[1]));
        }
        if (($argumentCount > 0))
        {
            ($value = $arguments[0]);
            if ((!\is_string($value)))
            {
                throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                    'foo',
                    0,
                    $arguments[0],
                    'string'
                ));
            }
        }
    }
    public function variableLength(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 1))
        {
            throw (new \Typhoon\Exception\MissingArgumentException('foo', 0, 'string'));
        }
        ($value = $arguments[0]);
        if ((!\is_string($value)))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'foo',
                0,
                $arguments[0],
                'string'
            ));
        }
        if (($argumentCount > 1))
        {
            ($check =             function ($argument, $index)
                        {
                            ($value = $argument);
                            if ((!\is_string($value)))
                            {
                                throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                                    'bar',
                                    $index,
                                    $argument,
                                    'string'
                                ));
                            }
                        }
            );
            for (($index = 1); ($index < $argumentCount); ($index++))
            {
                $check($arguments[$index], $index);
            }
        }
    }
}
