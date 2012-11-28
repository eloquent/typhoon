<?php
namespace Typhoon;


class DummyValidator extends Validator
{
    public function __call($name, array $arguments)
    {
    }
}
