<?php
namespace Typhoon\Eloquent\Typhoon\TestFixture\GeneratorExamples;


class TypicalClassTyphoon extends Typhoon\Validator
{
    public function validateConstruct(array $arguments)
    {
        ($argumentCount = \count($arguments));
        ($value = $arguments[0]);
        if ((!\is_string($value)))
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'foo',
                0,
                $arguments[0],
                'string'
            ));
        ($value = $arguments[1]);
        if ((!\is_int($value)))
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'bar',
                1,
                $arguments[1],
                'integer'
            ));
    }
    public function typicalMethod(array $arguments)
    {
        ($argumentCount = \count($arguments));
        ($value = $arguments[0]);
        if ((!\is_float($value)))
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'foo',
                0,
                $arguments[0],
                'float'
            ));
        if (($argumentCount > 1))
        {
            ($value = $arguments[1]);
        }
        if (($argumentCount > 2))
        {
            ($check =             function ($argument, $index)
                        {
                            ($value = $argument);
                            ($check =                 function ($value)
                                            {
                                                if ((!(\is_resource($value) && (\get_resource_type($value) === 'stream'))))
                                                    return false;
                                                ($streamMetaData = stream_get_meta_data($value));
                                                if ((!\preg_match('/[waxc+]/', $streamMetaData['mode'])))
                                                    return false;
                                            }
                            );
                            if ((!$check($argument)))
                                throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                                    'baz',
                                    $index,
                                    $argument,
                                    'stream {writable: true}'
                                ));
                        }
            );
            for (($index = 2); ($index < $argumentCount); ($index++))
            {
                $check($arguments[$index], $index);
            }
        }
    }
    public function undocumentedMethod(array $arguments)
    {
        if ((\count($arguments) > 0))
            throw (new \Typhoon\Exception\UnexpectedArgumentException(0, $arguments[0]));
    }
}
