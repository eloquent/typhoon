<?php
namespace Eloquent\Typhoon\TypeCheck\Validator\Eloquent\Typhoon\Console\Command;

class CheckCommandTypeCheck extends \Eloquent\Typhoon\TypeCheck\AbstractValidator
{
    public function validateConstruct(array $arguments)
    {
        $argumentCount = \count($arguments);
        if ($argumentCount > 3) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(3, $arguments[3]);
        }
    }

    public function analyzer(array $arguments)
    {
        if (\count($arguments) > 0) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(0, $arguments[0]);
        }
    }

    public function issueRenderer(array $arguments)
    {
        if (\count($arguments) > 0) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(0, $arguments[0]);
        }
    }

    public function configure(array $arguments)
    {
        if (\count($arguments) > 0) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(0, $arguments[0]);
        }
    }

    public function execute(array $arguments)
    {
        $argumentCount = \count($arguments);
        if ($argumentCount < 2) {
            if ($argumentCount < 1) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('input', 0, 'Symfony\\Component\\Console\\Input\\InputInterface');
            }
            throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('output', 1, 'Symfony\\Component\\Console\\Output\\OutputInterface');
        } elseif ($argumentCount > 2) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(2, $arguments[2]);
        }
    }

    public function generateErrorBlock(array $arguments)
    {
        $argumentCount = \count($arguments);
        if ($argumentCount < 1) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('result', 0, 'Eloquent\\Typhoon\\CodeAnalysis\\AnalysisResult');
        } elseif ($argumentCount > 1) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(1, $arguments[1]);
        }
    }

    public function generateWarningBlock(array $arguments)
    {
        $argumentCount = \count($arguments);
        if ($argumentCount < 1) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('result', 0, 'Eloquent\\Typhoon\\CodeAnalysis\\AnalysisResult');
        } elseif ($argumentCount > 1) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(1, $arguments[1]);
        }
    }

    public function generateBlock(array $arguments)
    {
        $argumentCount = \count($arguments);
        if ($argumentCount < 4) {
            if ($argumentCount < 1) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('label', 0, 'string');
            }
            if ($argumentCount < 2) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('blockStyle', 1, 'string');
            }
            if ($argumentCount < 3) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('result', 2, 'Eloquent\\Typhoon\\CodeAnalysis\\AnalysisResult');
            }
            throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('severity', 3, 'Eloquent\\Typhoon\\CodeAnalysis\\Issue\\IssueSeverity');
        } elseif ($argumentCount > 4) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(4, $arguments[4]);
        }
        $value = $arguments[0];
        if (!\is_string($value)) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                'label',
                0,
                $arguments[0],
                'string'
            );
        }
        $value = $arguments[1];
        if (!\is_string($value)) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                'blockStyle',
                1,
                $arguments[1],
                'string'
            );
        }
    }

}
