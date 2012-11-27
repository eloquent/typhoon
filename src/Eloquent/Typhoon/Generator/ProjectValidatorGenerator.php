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

use Eloquent\Typhoon\ClassMapper\ClassMapper;
use Eloquent\Typhoon\Configuration\Configuration;
use Icecave\Isolator\Isolator;
use Typhoon\Typhoon;

class ProjectValidatorGenerator
{
    /**
     * @param ClassMapper|null             $classMapper
     * @param ValidatorClassGenerator|null $classGenerator
     * @param FacadeGenerator|null         $facadeGenerator
     * @param Isolator|null                $isolator
     */
    public function __construct(
        ClassMapper $classMapper = null,
        ValidatorClassGenerator $classGenerator = null,
        FacadeGenerator $facadeGenerator = null,
        Isolator $isolator = null
    ) {
        $this->typhoon = Typhoon::get(__CLASS__, func_get_args());
        if (null === $classMapper) {
            $classMapper = new ClassMapper;
        }
        if (null === $classGenerator) {
            $classGenerator = new ValidatorClassGenerator;
        }
        if (null === $facadeGenerator) {
            $facadeGenerator = new FacadeGenerator;
        }

        $this->classMapper = $classMapper;
        $this->classGenerator = $classGenerator;
        $this->facadeGenerator = $facadeGenerator;
        $this->isolator = Isolator::get($isolator);
    }

    /**
     * @return ClassMapper
     */
    public function classMapper()
    {
        $this->typhoon->classMapper(func_get_args());

        return $this->classMapper;
    }

    /**
     * @return ClassGenerator
     */
    public function classGenerator()
    {
        $this->typhoon->classGenerator(func_get_args());

        return $this->classGenerator;
    }

    /**
     * @return FacadeGenerator
     */
    public function facadeGenerator()
    {
        $this->typhoon->facadeGenerator(func_get_args());

        return $this->facadeGenerator;
    }

    /**
     * @param Configuration $configuration
     */
    public function generate(Configuration $configuration)
    {
        $this->typhoon->generate(func_get_args());

        $this->generateClassValidators($configuration);
        $this->generateFacade($configuration);
    }

    /**
     * @param Configuration $configuration
     */
    protected function generateClassValidators(Configuration $configuration)
    {
        $this->typhoon->generateClassValidators(func_get_args());

        $sourcePaths = $configuration->sourcePaths();
        foreach ($this->buildClassMap($sourcePaths) as $classDefinition) {
            $namespaceName = null;
            $className = null;
            $source = $this->classGenerator()->generate(
                $configuration,
                $classDefinition,
                $namespaceName,
                $className
            );

            $this->isolator->file_put_contents(
                $this->prepareOutputPath(
                    $configuration,
                    $namespaceName,
                    $className
                ),
                $source
            );
        }
    }

    /**
     * @param Configuration $configuration
     */
    protected function generateFacade(Configuration $configuration)
    {
        $this->typhoon->generateFacade(func_get_args());

        $source = $this->facadeGenerator()->generate(
            $configuration,
            $namespaceName,
            $className
        );

        $this->isolator->file_put_contents(
            $this->prepareOutputPath(
                $configuration,
                $namespaceName,
                $className
            ),
            $source
        );
    }

    /**
     * @param array<string> $classPaths
     *
     * @return array<ClassDefinition>
     */
    protected function buildClassMap(array $classPaths)
    {
        $this->typhoon->buildClassMap(func_get_args());

        $classMap = array();
        foreach ($classPaths as $classPath) {
            $classMap = array_merge(
                $classMap,
                $this->classMapper->classesByDirectory(
                    $classPath
                )
            );
        }

        return $classMap;
    }

    /**
     * @param Configuration $configuration
     * @param string        $namespaceName
     * @param string        $className
     *
     * @return string
     */
    protected function prepareOutputPath(
        Configuration $configuration,
        $namespaceName,
        $className
    ) {
        $this->typhoon->prepareOutputPath(func_get_args());

        $path = $this->outputPath($configuration, $namespaceName, $className);
        $parentPath = dirname($path);
        if (!$this->isolator->is_dir($parentPath)) {
            $this->isolator->mkdir($parentPath, 0777, true);
        }

        return $path;
    }

    /**
     * @param Configuration $configuration
     * @param string        $namespaceName
     * @param string        $className
     *
     * @return string
     */
    protected function outputPath(
        Configuration $configuration,
        $namespaceName,
        $className
    ) {
        $this->typhoon->outputPath(func_get_args());

        return sprintf(
            '%s/%s',
            $configuration->outputPath(),
            $this->PSRPath($namespaceName, $className)
        );
    }

    /**
     * @param string $namespaceName
     * @param string $className
     *
     * @return string
     */
    protected function PSRPath($namespaceName, $className)
    {
        $this->typhoon->PSRPath(func_get_args());

        return
            str_replace('\\', '/', $namespaceName).
            '/'.
            str_replace('_', '/', $className).
            '.php'
        ;
    }

    private $classMapper;
    private $classGenerator;
    private $facadeGenerator;
    private $isolator;
    private $typhoon;
}
