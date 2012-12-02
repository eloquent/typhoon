<?php
namespace Typhoon\Validator\Eloquent\Typhoon\Parameter;


class ParameterListTyphoon extends \Typhoon\AbstractValidator
{
    public function validateConstruct(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount > 2))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(2, $arguments[2]));
        }
        if (($argumentCount > 0))
        {
            ($value = $arguments[0]);
            ($check =             function ($value)
                        {
                            if ((!\is_array($value)))
                            {
                                return false;
                            }
                            foreach ($value as $key => $subValue)
                            {
                                if ((!($subValue instanceof \Eloquent\Typhoon\Parameter\Parameter)))
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
                    'parameters',
                    0,
                    $arguments[0],
                    'array<Eloquent\\Typhoon\\Parameter\\Parameter>'
                ));
            }
        }
        if (($argumentCount > 1))
        {
            ($value = $arguments[1]);
            if ((!\is_bool($value)))
            {
                throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                    'variableLength',
                    1,
                    $arguments[1],
                    'boolean'
                ));
            }
        }
    }
    public function parameters(array $arguments)
    {
        if ((\count($arguments) > 0))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(0, $arguments[0]));
        }
    }
    public function parameterByName(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 1))
        {
            throw (new \Typhoon\Exception\MissingArgumentException('name', 0, 'string'));
        }
        elseif (($argumentCount > 1))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(1, $arguments[1]));
        }
        ($value = $arguments[0]);
        if ((!\is_string($value)))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'name',
                0,
                $arguments[0],
                'string'
            ));
        }
    }
    public function isVariableLength(array $arguments)
    {
        if ((\count($arguments) > 0))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(0, $arguments[0]));
        }
    }
    public function requiredParameters(array $arguments)
    {
        if ((\count($arguments) > 0))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(0, $arguments[0]));
        }
    }
}
