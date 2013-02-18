<?php
namespace Eloquent\Typhoon\TypeCheck\Validator\Eloquent\Typhoon\ClassMapper;

class PropertyDefinitionTypeCheck extends \Eloquent\Typhoon\TypeCheck\AbstractValidator
{
    public function createReflector(array $arguments)
    {
        if (\count($arguments) > 0) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(0, $arguments[0]);
        }
    }

}
