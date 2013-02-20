<?php
namespace Typhoon;

class DummyValidator extends AbstractValidator
{
    public function __call($name, array $arguments)
    {
    }

}
