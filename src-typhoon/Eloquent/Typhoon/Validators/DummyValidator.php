<?php
namespace Eloquent\Typhoon\Validators;


class DummyValidator extends AbstractValidator
{
    public function __call($name, array $arguments)
    {
    }
}
