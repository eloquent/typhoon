<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Configuration;

use Eloquent\Composer\Configuration\ConfigurationReader as ComposerReader;
use Eloquent\Cosmos\ClassName;
use Eloquent\Typhoon\TypeCheck\TypeCheck;
use ErrorException;
use Icecave\Isolator\Isolator;
use Symfony\Component\Filesystem\Filesystem;
use stdClass;

class ConfigurationReader
{
    /**
     * @param Filesystem|null     $filesystemHelper
     * @param ComposerReader|null $composerReader
     * @param Isolator|null       $isolator
     */
    public function __construct(
        Filesystem $filesystemHelper = null,
        ComposerReader $composerReader = null,
        Isolator $isolator = null
    ) {
        $this->typeCheck = TypeCheck::get(__CLASS__, func_get_args());
        if (null === $filesystemHelper) {
            $filesystemHelper = new Filesystem;
        }
        if (null === $composerReader) {
            $composerReader = new ComposerReader;
        }

        $this->filesystemHelper = $filesystemHelper;
        $this->composerReader = $composerReader;
        $this->isolator = Isolator::get($isolator);
    }

    /**
     * @return Filesystem
     */
    public function filesystemHelper()
    {
        $this->typeCheck->filesystemHelper(func_get_args());

        return $this->filesystemHelper;
    }

    /**
     * @return ComposerReader
     */
    public function composerReader()
    {
        $this->typeCheck->composerReader(func_get_args());

        return $this->composerReader;
    }

    /**
     * @param string|null $path
     * @param boolean     $throwOnFailure
     *
     * @return Configuration|null
     */
    public function read($path = null, $throwOnFailure = false)
    {
        $this->typeCheck->read(func_get_args());
        if (null === $path) {
            $path = $this->isolator->getcwd();
        }

        if ($configuration = $this->readTyphoon($path)) {
            return $configuration;
        }
        if ($configuration = $this->readComposer($path)) {
            return $configuration;
        }

        if ($throwOnFailure) {
            throw new Exception\ConfigurationReadException($this->typhoonPath($path));
        }

        return null;
    }

    /**
     * @param string $path
     *
     * @return string
     */
    protected function typhoonPath($path)
    {
        $this->typeCheck->typhoonPath(func_get_args());

        return sprintf(
            '%s/typhoon.json',
            $path
        );
    }

    /**
     * @param string $path
     *
     * @return string
     */
    protected function composerPath($path)
    {
        $this->typeCheck->composerPath(func_get_args());

        return sprintf(
            '%s/composer.json',
            $path
        );
    }

    /**
     * @param string $path
     *
     * @return Configuration|null
     */
    protected function readTyphoon($path)
    {
        $this->typeCheck->readTyphoon(func_get_args());

        $typhoonPath = $this->typhoonPath($path);
        if (!$this->isolator->is_file($typhoonPath)) {
            return null;
        }

        return $this->buildConfiguration(
            $this->loadJSON($typhoonPath)
        );
    }

    /**
     * @param string $path
     *
     * @return Configuration|null
     */
    protected function readComposer($path)
    {
        $this->typeCheck->readComposer(func_get_args());

        $composerPath = $this->composerPath($path);
        if (!$this->isolator->is_file($composerPath)) {
            return null;
        }

        $composerData = $this->composerReader()->read($composerPath);
        if (
            $composerData->extra() instanceof stdClass &&
            property_exists($composerData->extra(), 'typhoon') &&
            $composerData->extra()->typhoon instanceof stdClass
        ) {
            $typhoonData = $composerData->extra()->typhoon;
        } else {
            $typhoonData = new stdClass;
        }

        if (!property_exists($typhoonData, ConfigurationOption::SOURCE_PATHS()->value())) {
            $sourcePaths = $composerData->allSourcePaths();

            if (!property_exists($typhoonData, ConfigurationOption::OUTPUT_PATH()->value())) {
                $typhoonData->{ConfigurationOption::OUTPUT_PATH()->value()} = $this->inferOutputPath(
                    $sourcePaths
                );
            }

            $typhoonData->{ConfigurationOption::SOURCE_PATHS()->value()} = array();
            foreach ($sourcePaths as $sourcePath) {
                if (!$this->pathIsDescandantOrEqual(
                    $path,
                    $typhoonData->{ConfigurationOption::OUTPUT_PATH()->value()},
                    $sourcePath
                )) {
                    $typhoonData->{ConfigurationOption::SOURCE_PATHS()->value()}[] = $sourcePath;
                }
            }
        } elseif (!property_exists($typhoonData, ConfigurationOption::OUTPUT_PATH()->value())) {
            $typhoonData->{ConfigurationOption::OUTPUT_PATH()->value()} = $this->inferOutputPath(
                $typhoonData->{ConfigurationOption::SOURCE_PATHS()->value()}
            );
        }

        if (!property_exists($typhoonData, ConfigurationOption::VALIDATOR_NAMESPACE()->value())) {
            $validatorNamespace = array_search(
                array($typhoonData->{ConfigurationOption::OUTPUT_PATH()->value()}),
                $composerData->autoloadPSR0(),
                true
            );

            if (false === $validatorNamespace) {
                $mainNamespace = null;
                $mainNamespaceLength = null;
                foreach ($composerData->autoloadPSR0() as $namespace => $paths) {
                    $namespaceLength = count(explode('\\', $namespace));
                    if (
                        null === $mainNamespace ||
                        $namespace < $mainNamespaceLength
                    ) {
                        $mainNamespace = $namespace;
                        $mainNamespaceLength = $namespaceLength;
                    }
                }

                if (null !== $mainNamespace) {
                    $typhoonData->{ConfigurationOption::VALIDATOR_NAMESPACE()->value()} =
                        sprintf('%s\\TypeCheck', $mainNamespace)
                    ;
                }
            } else {
                $typhoonData->{ConfigurationOption::VALIDATOR_NAMESPACE()->value()} =
                    $validatorNamespace
                ;
            }
        }

        if (!property_exists($typhoonData, ConfigurationOption::USE_NATIVE_CALLABLE()->value())) {
            $composerDependencies = $composerData->dependencies();
            $phpConstraint = null;
            if (array_key_exists('php', $composerDependencies)) {
                $phpConstraint = $composerDependencies['php'];
            } elseif (array_key_exists('php-64bit', $composerDependencies)) {
                $phpConstraint = $composerDependencies['php-64bit'];
            }

            $useNativeCallable = false;
            if (null !== $phpConstraint) {
                foreach (explode(',', $phpConstraint) as $constraint) {
                    if (
                        preg_match('/>=?\s*(\d+(?:\.\d+)?(?:\.\d+)?)/', $constraint, $matches) &&
                        version_compare($matches[1], '5.4', '>=')
                    ) {
                        $useNativeCallable = true;
                    }
                }
            }

            $typhoonData->{ConfigurationOption::USE_NATIVE_CALLABLE()->value()} =
                $useNativeCallable
            ;
        }

        return $this->buildConfiguration($typhoonData);
    }

    /**
     * @param mixed $data
     *
     * @return Configuration
     */
    protected function buildConfiguration($data)
    {
        $this->typeCheck->buildConfiguration(func_get_args());

        $this->validateData($data);

        if (property_exists($data, ConfigurationOption::OUTPUT_PATH()->value())) {
            $outputPath = $data->{ConfigurationOption::OUTPUT_PATH()->value()};
        } else {
            $outputPath = $this->inferOutputPath(
                $data->{ConfigurationOption::SOURCE_PATHS()->value()}
            );
        }

        $configuration = new Configuration(
            $outputPath,
            $data->{ConfigurationOption::SOURCE_PATHS()->value()}
        );

        if (property_exists($data, ConfigurationOption::LOADER_PATHS()->value())) {
            $configuration->setLoaderPaths(
                $data->{ConfigurationOption::LOADER_PATHS()->value()}
            );
        }
        if (property_exists($data, ConfigurationOption::VALIDATOR_NAMESPACE()->value())) {
            $configuration->setValidatorNamespace(
                ClassName::fromString(
                    $data->{ConfigurationOption::VALIDATOR_NAMESPACE()->value()}
                )
            );
        }
        if (property_exists($data, ConfigurationOption::USE_NATIVE_CALLABLE()->value())) {
            $configuration->setUseNativeCallable(
                $data->{ConfigurationOption::USE_NATIVE_CALLABLE()->value()}
            );
        }

        return $configuration;
    }

    /**
     * @param string $path
     *
     * @return mixed
     */
    protected function loadJSON($path)
    {
        $this->typeCheck->loadJSON(func_get_args());

        $data = json_decode($this->load($path));
        $lastJSONError = json_last_error();
        if (JSON_ERROR_NONE !== $lastJSONError) {
            throw new Exception\InvalidJSONException($lastJSONError, $path);
        }

        return $data;
    }

    /**
     * @param string $path
     *
     * @return string
     */
    protected function load($path)
    {
        $this->typeCheck->load(func_get_args());

        try {
            $data = $this->isolator->file_get_contents($path);
        } catch (ErrorException $e) {
            throw new Exception\ConfigurationReadException($path, $e);
        }

        return $data;
    }

    /**
     * @param mixed $data
     */
    protected function validateData($data)
    {
        $this->typeCheck->validateData(func_get_args());

        if (!$data instanceof stdClass) {
            throw new Exception\InvalidConfigurationException(
                'Typhoon configuration data must be an object.'
            );
        }
        foreach ($data as $optionName => $value) {
            ConfigurationOption::instanceByValue($optionName);
        }

        // output path
        if (property_exists($data, ConfigurationOption::OUTPUT_PATH()->value())) {
            if (!is_string($data->{ConfigurationOption::OUTPUT_PATH()->value()})) {
                throw new Exception\InvalidConfigurationException(
                    "Output path must be a string."
                );
            }
        }

        // source paths
        if (!property_exists($data, ConfigurationOption::SOURCE_PATHS()->value())) {
            throw new Exception\InvalidConfigurationException(
                "At least one source path is required."
            );
        }
        if (!is_array($data->{ConfigurationOption::SOURCE_PATHS()->value()})) {
            throw new Exception\InvalidConfigurationException(
                "Source paths must be an array."
            );
        }
        foreach ($data->{ConfigurationOption::SOURCE_PATHS()->value()} as $sourcePath) {
            if (!is_string($sourcePath)) {
                throw new Exception\InvalidConfigurationException(
                    "Entries in source paths must be strings."
                );
            }
        }

        // loader paths
        if (property_exists($data, ConfigurationOption::LOADER_PATHS()->value())) {
            if (!is_array($data->{ConfigurationOption::LOADER_PATHS()->value()})) {
                throw new Exception\InvalidConfigurationException(
                    "Loader paths must be an array."
                );
            }
            foreach ($data->{ConfigurationOption::LOADER_PATHS()->value()} as $loaderPath) {
                if (!is_string($loaderPath)) {
                    throw new Exception\InvalidConfigurationException(
                        "Entries in loader paths must be strings."
                    );
                }
            }
        }

        // validator namespace
        if (property_exists($data, ConfigurationOption::VALIDATOR_NAMESPACE()->value())) {
            if (!is_string($data->{ConfigurationOption::VALIDATOR_NAMESPACE()->value()})) {
                throw new Exception\InvalidConfigurationException(
                    "Validator namespace must be a string."
                );
            }
        }

        // use native callable
        if (property_exists($data, ConfigurationOption::USE_NATIVE_CALLABLE()->value())) {
            if (!is_bool($data->{ConfigurationOption::USE_NATIVE_CALLABLE()->value()})) {
                throw new Exception\InvalidConfigurationException(
                    "Use native callable option must be a boolean."
                );
            }
        }
    }

    /**
     * @param array<string> $sourcePaths
     *
     * @return string
     */
    protected function inferOutputPath(array $sourcePaths)
    {
        $this->typeCheck->inferOutputPath(func_get_args());

        if (in_array('src', $sourcePaths, true)) {
            return 'src-typhoon';
        }
        if (in_array('lib', $sourcePaths, true)) {
            return 'lib-typhoon';
        }

        return 'src-typhoon';
    }

    /**
     * @param string $workingPath
     * @param string $ancestor
     * @param string $descendant
     *
     * @return boolean
     */
    protected function pathIsDescandantOrEqual(
        $workingPath,
        $ancestor,
        $descendant
    ) {
        $this->typeCheck->pathIsDescandantOrEqual(func_get_args());

        $ancestor = $this->normalizePath($workingPath, $ancestor);
        $descendant = $this->normalizePath($workingPath, $descendant);

        return 0 === strpos($descendant, $ancestor);
    }

    /**
     * @param string $workingPath
     * @param string $path
     *
     * @return string
     */
    protected function normalizePath($workingPath, $path)
    {
        $this->typeCheck->normalizePath(func_get_args());

        if ($this->filesystemHelper->isAbsolutePath($path)) {
            $path = $this->filesystemHelper->makePathRelative(
                $path,
                $workingPath
            );
        }

        return implode(
            '/',
            array_filter(
                explode('/', str_replace('\\', '/', $path)),
                function ($atom) {
                    return '.' !== $atom;
                }
            )
        );
    }

    private $filesystemHelper;
    private $composerReader;
    private $isolator;
    private $typeCheck;
}
