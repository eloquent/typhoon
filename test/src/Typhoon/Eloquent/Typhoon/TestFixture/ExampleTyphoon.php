<?php
namespace Typhoon;


class Typhoon
{
    public static function get($className, array $arguments = NULL)
    {
        if (DummyValidator())
        {
            return (new DummyValidator());
        }
    }
}
