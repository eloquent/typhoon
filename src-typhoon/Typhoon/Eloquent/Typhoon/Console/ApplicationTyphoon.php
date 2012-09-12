<?php
namespace Typhoon\Eloquent\Typhoon\Console;


class ApplicationTyphoon extends \Typhoon\Validator
{
    public function validateConstruct(array $arguments)
    {
        if ((\count($arguments) > 0))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(0, $arguments[0]));
        }
    }
}
