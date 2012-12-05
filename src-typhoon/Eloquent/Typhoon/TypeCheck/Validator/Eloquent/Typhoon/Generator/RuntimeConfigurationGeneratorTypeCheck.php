<?php
namespace Eloquent\Typhoon\TypeCheck\Validator\Eloquent\Typhoon\Generator;


class RuntimeConfigurationGeneratorTypeCheck extends \Eloquent\Typhoon\TypeCheck\AbstractValidator
{
    public function validateConstruct(array $arguments)
    {
        if ((\count($arguments) > 0))
        {
            throw (new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(0, $arguments[0]));
        }
    }
    public function generate(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 1))
        {
            throw (new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('configuration', 0, 'Eloquent\\Typhoon\\Configuration\\RuntimeConfiguration'));
        }
        elseif (($argumentCount > 1))
        {
            throw (new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(1, $arguments[1]));
        }
    }
}
