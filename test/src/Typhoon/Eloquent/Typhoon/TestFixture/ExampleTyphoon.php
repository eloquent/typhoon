<?php
namespace Typhoon;


class Typhoon
{
    public static function get($className, array $arguments = null)
    {
        if (DummyValidator())
        {
            return (new DummyValidator());
        }
    }
}
