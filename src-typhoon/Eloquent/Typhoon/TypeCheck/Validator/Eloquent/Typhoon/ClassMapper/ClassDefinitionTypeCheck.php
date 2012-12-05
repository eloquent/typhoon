<?php
namespace Eloquent\Typhoon\TypeCheck\Validator\Eloquent\Typhoon\ClassMapper;


class ClassDefinitionTypeCheck extends \Eloquent\Typhoon\TypeCheck\AbstractValidator
{
    public function validateConstruct(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 1))
        {
            throw (new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('className', 0, 'string'));
        }
        elseif (($argumentCount > 3))
        {
            throw (new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(3, $arguments[3]));
        }
        ($value = $arguments[0]);
        if ((!\is_string($value)))
        {
            throw (new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                'className',
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
                throw (new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
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
            ($check =             function ($value)
                        {
                            if ((!\is_array($value)))
                            {
                                return false;
                            }
                            foreach ($value as $key => $subValue)
                            {
                                if ((!\is_string($key)))
                                {
                                    return false;
                                }
                                if ((!(\is_string($subValue) || ($subValue === null))))
                                {
                                    return false;
                                }
                            }
                            return true;
                        }
            );
            if ((!$check($arguments[2])))
            {
                throw (new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                    'usedClasses',
                    2,
                    $arguments[2],
                    'array<string, string|null>'
                ));
            }
        }
    }
    public function className(array $arguments)
    {
        if ((\count($arguments) > 0))
        {
            throw (new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(0, $arguments[0]));
        }
    }
    public function canonicalClassName(array $arguments)
    {
        if ((\count($arguments) > 0))
        {
            throw (new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(0, $arguments[0]));
        }
    }
    public function namespaceName(array $arguments)
    {
        if ((\count($arguments) > 0))
        {
            throw (new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(0, $arguments[0]));
        }
    }
    public function usedClasses(array $arguments)
    {
        if ((\count($arguments) > 0))
        {
            throw (new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(0, $arguments[0]));
        }
    }
    public function classNameResolver(array $arguments)
    {
        if ((\count($arguments) > 0))
        {
            throw (new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(0, $arguments[0]));
        }
    }
}
