<?php
namespace Typhoon;


class Typhoon
{
    public static function get($className, array $arguments = null)
    {
        if (static::$dummyMode)
        {
            return (new DummyValidator());
        }
        if ((!\array_key_exists($className, static::$instances)))
        {
            static::install($className, static::createValidator($className));
        }
        ($validator = static::$instances[$className]);
        if ((null !== $arguments))
        {
            $validator->validateConstruct($arguments);
        }
        return $validator;
    }
    public static function install($className, $validator)
    {
        (static::$instances[$className] = $validator);
    }
}
