<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Generator;

use Eloquent\Cosmos\ClassName;
use Eloquent\Typhoon\ClassMapper\ClassMapper;
use Eloquent\Typhoon\Configuration\Configuration;
use Eloquent\Typhoon\TypeCheck\TypeCheck;
use Icecave\Isolator\Isolator;

class ProjectValidatorGenerator
{
    /**
     * @param ClassMapper|null                 $classMapper
     * @param ValidatorClassGenerator|null     $validatorClassGenerator
     * @param array<StaticClassGenerator>|null $staticClassGenerators
     * @param Isolator|null                    $isolator
     */
    public function __construct(
        ClassMapper $classMapper = null,
        ValidatorClassGenerator $validatorClassGenerator = null,
        array $staticClassGenerators = null,
        Isolator $isolator = null
    ) {
        $this->typeCheck = TypeCheck::get(__CLASS__, func_get_args());
        if (null === $classMapper) {
            $classMapper = new ClassMapper;
        }
        if (null === $validatorClassGenerator) {
            $validatorClassGenerator = new ValidatorClassGenerator;
        }
        if (null === $staticClassGenerators) {
            $staticClassGenerators = array(
                new FacadeGenerator,
                new AbstractValidatorGenerator,
                new DummyValidatorGenerator,
                new TypeInspectorGenerator,
                new ExceptionGenerator\UnexpectedInputExceptionGenerator,
                new ExceptionGenerator\MissingArgumentExceptionGenerator,
                new ExceptionGenerator\UnexpectedArgumentExceptionGenerator,
                new ExceptionGenerator\UnexpectedArgumentValueExceptionGenerator,
            );
        }

        $this->classMapper = $classMapper;
        $this->validatorClassGenerator = $validatorClassGenerator;
        $this->staticClassGenerators = $staticClassGenerators;
        $this->isolator = Isolator::get($isolator);
    }

    /**
     * @return ClassMapper
     */
    public function classMapper()
    {
        $this->typeCheck->classMapper(func_get_args());

        return $this->classMapper;
    }

    /**
     * @return ClassGenerator
     */
    public function validatorClassGenerator()
    {
        $this->typeCheck->validatorClassGenerator(func_get_args());

        return $this->validatorClassGenerator;
    }

    /**
     * @return array<StaticClassGenerator>
     */
    public function staticClassGenerators()
    {
        $this->typeCheck->staticClassGenerators(func_get_args());

        return $this->staticClassGenerators;
    }

    /**
     * @param Configuration $configuration
     */
    public function generate(Configuration $configuration)
    {
        $this->typeCheck->generate(func_get_args());

        $this->generateClassValidators($configuration);
        $this->generateStaticClasses($configuration);
    }

    /**
     * @param Configuration $configuration
     */
    protected function generateClassValidators(Configuration $configuration)
    {
        $this->typeCheck->generateClassValidators(func_get_args());

        $sourcePaths = $configuration->sourcePaths();
        foreach ($this->classMapper()->classesByPaths($sourcePaths) as $classDefinition) {
            $className = null;
            $source = $this->validatorClassGenerator()->generate(
                $configuration,
                $classDefinition,
                $className
            );

            $this->isolator->file_put_contents(
                $this->prepareOutputPath(
                    $configuration,
                    $className
                ),
                $source
            );
        }
    }

    /**
     * @param Configuration $configuration
     */
    protected function generateStaticClasses(Configuration $configuration)
    {
        $this->typeCheck->generateStaticClasses(func_get_args());

        foreach ($this->staticClassGenerators() as $generator) {
            $className = null;
            $source = $generator->generate(
                $configuration,
                $className
            );

            $this->isolator->file_put_contents(
                $this->prepareOutputPath(
                    $configuration,
                    $className
                ),
                $source
            );
        }
    }

    /**
     * @param Configuration $configuration
     * @param ClassName     $className
     *
     * @return string
     */
    protected function prepareOutputPath(
        Configuration $configuration,
        ClassName $className
    ) {
        $this->typeCheck->prepareOutputPath(func_get_args());

        $path = $this->outputPath($configuration, $className);
        $parentPath = dirname($path);
        if (!$this->isolator->is_dir($parentPath)) {
            $this->isolator->mkdir($parentPath, 0777, true);
        }

        return $path;
    }

    /**
     * @param Configuration $configuration
     * @param ClassName     $className
     *
     * @return string
     */
    protected function outputPath(
        Configuration $configuration,
        ClassName $className
    ) {
        $this->typeCheck->outputPath(func_get_args());

        return sprintf(
            '%s%s',
            $configuration->outputPath(),
            $this->PSRPath($className)
        );
    }

    /**
     * @param ClassName $className
     *
     * @return string
     */
    protected function PSRPath(ClassName $className)
    {
        $this->typeCheck->PSRPath(func_get_args());

        return
            str_replace('\\', '/', $className->parent()->string()).
            '/'.
            str_replace('_', '/', $className->shortName()->string()).
            '.php'
        ;
    }

    private $classMapper;
    private $validatorClassGenerator;
    private $staticClassGenerators;
    private $isolator;
    private $typeCheck;
}
