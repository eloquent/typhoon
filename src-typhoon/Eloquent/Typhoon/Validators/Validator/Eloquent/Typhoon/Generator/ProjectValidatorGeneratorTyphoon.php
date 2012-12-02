<?php
namespace Eloquent\Typhoon\Validators\Validator\Eloquent\Typhoon\Generator;


class ProjectValidatorGeneratorTyphoon extends \Eloquent\Typhoon\Validators\AbstractValidator
{
    public function validateConstruct(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount > 4))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(4, $arguments[4]));
        }
        if (($argumentCount > 2))
        {
            ($value = $arguments[2]);
            ($check =             function ($value)
                        {
                            ($check =                 function ($value)
                                            {
                                                if ((!\is_array($value)))
                                                {
                                                    return false;
                                                }
                                                foreach ($value as $key => $subValue)
                                                {
                                                    if ((!($subValue instanceof \Eloquent\Typhoon\Generator\StaticClassGenerator)))
                                                    {
                                                        return false;
                                                    }
                                                }
                                                return true;
                                            }
                            );
                            if (                function ($value)
                                            {
                                                if ((!\is_array($value)))
                                                {
                                                    return false;
                                                }
                                                foreach ($value as $key => $subValue)
                                                {
                                                    if ((!($subValue instanceof \Eloquent\Typhoon\Generator\StaticClassGenerator)))
                                                    {
                                                        return false;
                                                    }
                                                }
                                                return true;
                                            }
                            )
                            {
                                return true;
                            }
                            return ($value === null);
                        }
            );
            if ((!$check($arguments[2])))
            {
                throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                    'staticClassGenerators',
                    2,
                    $arguments[2],
                    'array<Eloquent\\Typhoon\\Generator\\StaticClassGenerator>|null'
                ));
            }
        }
    }
    public function classMapper(array $arguments)
    {
        if ((\count($arguments) > 0))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(0, $arguments[0]));
        }
    }
    public function validatorClassGenerator(array $arguments)
    {
        if ((\count($arguments) > 0))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(0, $arguments[0]));
        }
    }
    public function staticClassGenerators(array $arguments)
    {
        if ((\count($arguments) > 0))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(0, $arguments[0]));
        }
    }
    public function generate(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 1))
        {
            throw (new \Typhoon\Exception\MissingArgumentException('configuration', 0, 'mixed'));
        }
        elseif (($argumentCount > 1))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(1, $arguments[1]));
        }
    }
    public function generateClassValidators(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 1))
        {
            throw (new \Typhoon\Exception\MissingArgumentException('configuration', 0, 'mixed'));
        }
        elseif (($argumentCount > 1))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(1, $arguments[1]));
        }
    }
    public function generateStaticClasses(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 1))
        {
            throw (new \Typhoon\Exception\MissingArgumentException('configuration', 0, 'mixed'));
        }
        elseif (($argumentCount > 1))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(1, $arguments[1]));
        }
    }
    public function buildClassMap(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 1))
        {
            throw (new \Typhoon\Exception\MissingArgumentException('classPaths', 0, 'array<string>'));
        }
        elseif (($argumentCount > 1))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(1, $arguments[1]));
        }
        ($value = $arguments[0]);
        ($check =         function ($value)
                {
                    if ((!\is_array($value)))
                    {
                        return false;
                    }
                    foreach ($value as $key => $subValue)
                    {
                        if ((!\is_string($subValue)))
                        {
                            return false;
                        }
                    }
                    return true;
                }
        );
        if ((!$check($arguments[0])))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'classPaths',
                0,
                $arguments[0],
                'array<string>'
            ));
        }
    }
    public function prepareOutputPath(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 3))
        {
            if (($argumentCount < 1))
            {
                throw (new \Typhoon\Exception\MissingArgumentException('configuration', 0, 'mixed'));
            }
            if (($argumentCount < 2))
            {
                throw (new \Typhoon\Exception\MissingArgumentException('namespaceName', 1, 'string'));
            }
            throw (new \Typhoon\Exception\MissingArgumentException('className', 2, 'string'));
        }
        elseif (($argumentCount > 3))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(3, $arguments[3]));
        }
        ($value = $arguments[1]);
        if ((!\is_string($value)))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'namespaceName',
                1,
                $arguments[1],
                'string'
            ));
        }
        ($value = $arguments[2]);
        if ((!\is_string($value)))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'className',
                2,
                $arguments[2],
                'string'
            ));
        }
    }
    public function outputPath(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 3))
        {
            if (($argumentCount < 1))
            {
                throw (new \Typhoon\Exception\MissingArgumentException('configuration', 0, 'mixed'));
            }
            if (($argumentCount < 2))
            {
                throw (new \Typhoon\Exception\MissingArgumentException('namespaceName', 1, 'string'));
            }
            throw (new \Typhoon\Exception\MissingArgumentException('className', 2, 'string'));
        }
        elseif (($argumentCount > 3))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(3, $arguments[3]));
        }
        ($value = $arguments[1]);
        if ((!\is_string($value)))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'namespaceName',
                1,
                $arguments[1],
                'string'
            ));
        }
        ($value = $arguments[2]);
        if ((!\is_string($value)))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'className',
                2,
                $arguments[2],
                'string'
            ));
        }
    }
    public function PSRPath(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 2))
        {
            if (($argumentCount < 1))
            {
                throw (new \Typhoon\Exception\MissingArgumentException('namespaceName', 0, 'string'));
            }
            throw (new \Typhoon\Exception\MissingArgumentException('className', 1, 'string'));
        }
        elseif (($argumentCount > 2))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(2, $arguments[2]));
        }
        ($value = $arguments[0]);
        if ((!\is_string($value)))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'namespaceName',
                0,
                $arguments[0],
                'string'
            ));
        }
        ($value = $arguments[1]);
        if ((!\is_string($value)))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'className',
                1,
                $arguments[1],
                'string'
            ));
        }
    }
}
