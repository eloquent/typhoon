<?php
namespace Eloquent\Typhoon\TypeCheck\Validator\Eloquent\Typhoon\Console\Command;


class GenerateCommandTypeCheck extends \Eloquent\Typhoon\TypeCheck\AbstractValidator
{
    public function validateConstruct(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount > 2))
        {
            throw (new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(2, $arguments[2]));
        }
    }
    public function generator(array $arguments)
    {
        if ((\count($arguments) > 0))
        {
            throw (new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(0, $arguments[0]));
        }
    }
    public function configure(array $arguments)
    {
        if ((\count($arguments) > 0))
        {
            throw (new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(0, $arguments[0]));
        }
    }
    public function execute(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 2))
        {
            if (($argumentCount < 1))
            {
                throw (new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('input', 0, 'Symfony\\Component\\Console\\Input\\InputInterface'));
            }
            throw (new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('output', 1, 'Symfony\\Component\\Console\\Output\\OutputInterface'));
        }
        elseif (($argumentCount > 2))
        {
            throw (new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(2, $arguments[2]));
        }
    }
}
