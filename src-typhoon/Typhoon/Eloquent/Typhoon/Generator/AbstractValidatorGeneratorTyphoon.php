<?php
namespace Typhoon\Eloquent\Typhoon\Generator;


class AbstractValidatorGeneratorTyphoon extends \Typhoon\Validator
{
    public function validateConstruct(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount > 1))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(1, $arguments[1]));
        }
    }
    public function renderer(array $arguments)
    {
        if ((\count($arguments) > 0))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(0, $arguments[0]));
        }
    }
    public function generate(array $arguments)
    {
        if ((\count($arguments) > 0))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(0, $arguments[0]));
        }
    }
    public function generateSyntaxTree(array $arguments)
    {
        if ((\count($arguments) > 0))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(0, $arguments[0]));
        }
    }
    public function generateConstructor(array $arguments)
    {
        if ((\count($arguments) > 0))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(0, $arguments[0]));
        }
    }
    public function generateCallMethod(array $arguments)
    {
        if ((\count($arguments) > 0))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(0, $arguments[0]));
        }
    }
}
