<?php
namespace Typhoon\Eloquent\Typhoon\TestFixture;

class ExampleTyphoon
{
    public function validateConstructor(array $arguments)
    {
        $this->arguments = $arguments;
    }

    public function arguments()
    {
        return $this->arguments;
    }

    private $arguments;
}
