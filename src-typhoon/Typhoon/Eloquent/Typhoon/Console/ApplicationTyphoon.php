<?php
namespace Typhoon\Eloquent\Typhoon\Console;

class ApplicationTyphoon
{
    public function __construct(array $arguments)
    {
        if (count($arguments) > 0) {
            throw new \InvalidArgumentException("Unexpected argument at index 1.");
        }
    }
}
