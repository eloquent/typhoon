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
use Eloquent\Typhoon\Generator\ExceptionGenerator\MissingArgumentExceptionGenerator;
use Eloquent\Typhoon\Generator\ExceptionGenerator\UnexpectedArgumentExceptionGenerator;
use Eloquent\Typhoon\Generator\ExceptionGenerator\UnexpectedArgumentValueExceptionGenerator;
use Eloquent\Typhoon\Generator\ExceptionGenerator\UnexpectedInputExceptionGenerator;
use Icecave\Isolator\Isolator;
use Typhoon\Typhoon;

class ProjectValidatorGenerator
{
    /**
     * @param ClassMapper|null                               $classMapper
     * @param ValidatorClassGenerator|null                   $validatorClassGenerator
     * @param FacadeGenerator|null                           $facadeGenerator
     * @param AbstractValidatorGenerator|null                $abstractValidatorGenerator
     * @param DummyValidatorGenerator|null                   $dummyValidatorGenerator
     * @param TypeInspectorGenerator|null                    $typeInspectorGenerator
     * @param UnexpectedInputExceptionGenerator|null         $unexpectedInputExceptionGenerator
     * @param MissingArgumentExceptionGenerator|null         $missingArgumentExceptionGenerator
     * @param UnexpectedArgumentExceptionGenerator|null      $unexpectedArgumentExceptionGenerator
     * @param UnexpectedArgumentValueExceptionGenerator|null $unexpectedArgumentValueExceptionGenerator
     * @param Isolator|null                                  $isolator
     */
    public function __construct(
        ClassMapper $classMapper = null,
        ValidatorClassGenerator $validatorClassGenerator = null,
        FacadeGenerator $facadeGenerator = null,
        AbstractValidatorGenerator $abstractValidatorGenerator = null,
        DummyValidatorGenerator $dummyValidatorGenerator = null,
        TypeInspectorGenerator $typeInspectorGenerator = null,
        UnexpectedInputExceptionGenerator $unexpectedInputExceptionGenerator = null,
        MissingArgumentExceptionGenerator $missingArgumentExceptionGenerator = null,
        UnexpectedArgumentExceptionGenerator $unexpectedArgumentExceptionGenerator = null,
        UnexpectedArgumentValueExceptionGenerator $unexpectedArgumentValueExceptionGenerator = null,
        Isolator $isolator = null
    ) {
        $this->typhoon = Typhoon::get(__CLASS__, func_get_args());
        if (null === $classMapper) {
            $classMapper = new ClassMapper;
        }
        if (null === $validatorClassGenerator) {
            $validatorClassGenerator = new ValidatorClassGenerator;
        }
        if (null === $facadeGenerator) {
            $facadeGenerator = new FacadeGenerator;
        }
        if (null === $abstractValidatorGenerator) {
            $abstractValidatorGenerator = new AbstractValidatorGenerator;
        }
        if (null === $dummyValidatorGenerator) {
            $dummyValidatorGenerator = new DummyValidatorGenerator;
        }
        if (null === $typeInspectorGenerator) {
            $typeInspectorGenerator = new TypeInspectorGenerator;
        }
        if (null === $unexpectedInputExceptionGenerator) {
            $unexpectedInputExceptionGenerator = new UnexpectedInputExceptionGenerator;
        }
        if (null === $missingArgumentExceptionGenerator) {
            $missingArgumentExceptionGenerator = new MissingArgumentExceptionGenerator;
        }
        if (null === $unexpectedArgumentExceptionGenerator) {
            $unexpectedArgumentExceptionGenerator = new UnexpectedArgumentExceptionGenerator;
        }
        if (null === $unexpectedArgumentValueExceptionGenerator) {
            $unexpectedArgumentValueExceptionGenerator = new UnexpectedArgumentValueExceptionGenerator;
        }

        $this->classMapper = $classMapper;
        $this->validatorClassGenerator = $validatorClassGenerator;
        $this->facadeGenerator = $facadeGenerator;
        $this->abstractValidatorGenerator = $abstractValidatorGenerator;
        $this->dummyValidatorGenerator = $dummyValidatorGenerator;
        $this->typeInspectorGenerator = $typeInspectorGenerator;
        $this->unexpectedInputExceptionGenerator = $unexpectedInputExceptionGenerator;
        $this->missingArgumentExceptionGenerator = $missingArgumentExceptionGenerator;
        $this->unexpectedArgumentExceptionGenerator = $unexpectedArgumentExceptionGenerator;
        $this->unexpectedArgumentValueExceptionGenerator = $unexpectedArgumentValueExceptionGenerator;
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
    public function validatorClassGenerator()
    {
        $this->typhoon->validatorClassGenerator(func_get_args());

        return $this->validatorClassGenerator;
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
     * @return AbstractValidatorGenerator
     */
    public function abstractValidatorGenerator()
    {
        $this->typhoon->abstractValidatorGenerator(func_get_args());

        return $this->abstractValidatorGenerator;
    }

    /**
     * @return DummyValidatorGenerator
     */
    public function dummyValidatorGenerator()
    {
        $this->typhoon->dummyValidatorGenerator(func_get_args());

        return $this->dummyValidatorGenerator;
    }

    /**
     * @return TypeInspectorGenerator
     */
    public function typeInspectorGenerator()
    {
        $this->typhoon->typeInspectorGenerator(func_get_args());

        return $this->typeInspectorGenerator;
    }

    /**
     * @return UnexpectedInputExceptionGenerator
     */
    public function unexpectedInputExceptionGenerator()
    {
        $this->typhoon->unexpectedInputExceptionGenerator(func_get_args());

        return $this->unexpectedInputExceptionGenerator;
    }

    /**
     * @return MissingArgumentExceptionGenerator
     */
    public function missingArgumentExceptionGenerator()
    {
        $this->typhoon->missingArgumentExceptionGenerator(func_get_args());

        return $this->missingArgumentExceptionGenerator;
    }

    /**
     * @return UnexpectedArgumentExceptionGenerator
     */
    public function unexpectedArgumentExceptionGenerator()
    {
        $this->typhoon->unexpectedArgumentExceptionGenerator(func_get_args());

        return $this->unexpectedArgumentExceptionGenerator;
    }

    /**
     * @return UnexpectedArgumentValueExceptionGenerator
     */
    public function unexpectedArgumentValueExceptionGenerator()
    {
        $this->typhoon->unexpectedArgumentValueExceptionGenerator(func_get_args());

        return $this->unexpectedArgumentValueExceptionGenerator;
    }

    /**
     * @param Configuration $configuration
     */
    public function generate(Configuration $configuration)
    {
        $this->typhoon->generate(func_get_args());

        $this->generateClassValidators($configuration);
        $this->generateFacade($configuration);
        $this->generateAbstractValidator($configuration);
        $this->generateDummyValidator($configuration);
        $this->generateTypeInspector($configuration);
        $this->generateUnexpectedInputException($configuration);
        $this->generateMissingArgumentException($configuration);
        $this->generateUnexpectedArgumentException($configuration);
        $this->generateUnexpectedArgumentValueException($configuration);
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
            $source = $this->validatorClassGenerator()->generate(
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
     * @param Configuration $configuration
     */
    protected function generateAbstractValidator(Configuration $configuration)
    {
        $this->typhoon->generateAbstractValidator(func_get_args());

        $source = $this->abstractValidatorGenerator()->generate(
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
     * @param Configuration $configuration
     */
    protected function generateDummyValidator(Configuration $configuration)
    {
        $this->typhoon->generateDummyValidator(func_get_args());

        $source = $this->dummyValidatorGenerator()->generate(
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
     * @param Configuration $configuration
     */
    protected function generateTypeInspector(Configuration $configuration)
    {
        $this->typhoon->generateTypeInspector(func_get_args());

        $source = $this->typeInspectorGenerator()->generate(
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
     * @param Configuration $configuration
     */
    protected function generateUnexpectedInputException(Configuration $configuration)
    {
        $this->typhoon->generateUnexpectedInputException(func_get_args());

        $source = $this->unexpectedInputExceptionGenerator()->generate(
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
     * @param Configuration $configuration
     */
    protected function generateMissingArgumentException(Configuration $configuration)
    {
        $this->typhoon->generateMissingArgumentException(func_get_args());

        $source = $this->missingArgumentExceptionGenerator()->generate(
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
     * @param Configuration $configuration
     */
    protected function generateUnexpectedArgumentException(Configuration $configuration)
    {
        $this->typhoon->generateUnexpectedArgumentException(func_get_args());

        $source = $this->unexpectedArgumentExceptionGenerator()->generate(
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
     * @param Configuration $configuration
     */
    protected function generateUnexpectedArgumentValueException(Configuration $configuration)
    {
        $this->typhoon->generateUnexpectedArgumentValueException(func_get_args());

        $source = $this->unexpectedArgumentValueExceptionGenerator()->generate(
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
    private $validatorClassGenerator;
    private $facadeGenerator;
    private $abstractValidatorGenerator;
    private $dummyValidatorGenerator;
    private $typeInspectorGenerator;
    private $unexpectedInputExceptionGenerator;
    private $missingArgumentExceptionGenerator;
    private $unexpectedArgumentExceptionGenerator;
    private $unexpectedArgumentValueExceptionGenerator;
    private $isolator;
    private $typhoon;
}
