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
use Icecave\Isolator\Isolator;

class ProjectValidatorGenerator
{
    /**
     * @param ClassMapper|null $classMapper
     * @param ValidatorClassGenerator|null $classGenerator
     * @param Isolator|null $isolator
     */
    public function __construct(
        ClassMapper $classMapper = null,
        ValidatorClassGenerator $classGenerator = null,
        Isolator $isolator = null
    ) {
        if (null === $classMapper) {
            $classMapper = new ClassMapper;
        }
        if (null === $classGenerator) {
            $classGenerator = new ValidatorClassGenerator;
        }

        $this->classMapper = $classMapper;
        $this->classGenerator = $classGenerator;
        $this->isolator = Isolator::get($isolator);
    }

    /**
     * @return ClassMapper
     */
    public function classMapper()
    {
        return $this->classMapper;
    }

    /**
     * @return ClassGenerator
     */
    public function classGenerator()
    {
        return $this->classGenerator;
    }

    /**
     * @param string $outputPath
     * @param array<string> $classPaths
     */
    public function generate($outputPath, array $classPaths)
    {
        foreach ($this->buildClassMap($classPaths) as $classDefinition) {
            $namespaceName = null;
            $className = null;
            $source = $this->classGenerator()->generate(
                $classDefinition,
                $namespaceName,
                $className
            );

            $path =
                $outputPath.'/'.
                $this->PSRPath($namespaceName, $className)
            ;

            $parentPath = dirname($path);
            if (!$this->isolator->is_dir($parentPath)) {
                $this->isolator->mkdir($parentPath, 0777, true);
            }

            $this->isolator->file_put_contents(
                $path,
                $source
            );
        }
    }

    /**
     * @param array<string> $classPaths
     *
     * @return array<ClassDefinition>
     */
    protected function buildClassMap(array $classPaths)
    {
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
     * @param string $namespaceName
     * @param string $className
     *
     * @return string
     */
    protected function PSRPath($namespaceName, $className)
    {
        return
            str_replace('\\', '/', $namespaceName).
            '/'.
            str_replace('_', '/', $className).
            '.php'
        ;
    }

    private $classMapper;
    private $classGenerator;
    private $isolator;
}
