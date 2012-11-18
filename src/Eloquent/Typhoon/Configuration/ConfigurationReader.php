<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Configuration;

use ErrorException;
use Icecave\Isolator\Isolator;
use Symfony\Component\Filesystem\Filesystem;
use stdClass;
use Typhoon\Typhoon;

class ConfigurationReader
{
    /**
     * @param Isolator|null $isolator
     */
    public function __construct(Isolator $isolator = null)
    {
        $this->typhoon = Typhoon::get(__CLASS__, func_get_args());

        $this->filesystemHelper = new Filesystem;
        $this->isolator = Isolator::get($isolator);
    }

    /**
     * @param string|null $path
     *
     * @return Configuration|null
     */
    public function read($path = null)
    {
        $this->typhoon->read(func_get_args());
        if (null === $path) {
            $path = $this->isolator->getcwd();
        }

        if ($configuration = $this->readTyphoon($path)) {
            return $configuration;
        }
        if ($configuration = $this->readComposer($path)) {
            return $configuration;
        }

        return null;
    }

    /**
     * @param string $path
     *
     * @return Configuration|null
     */
    protected function readTyphoon($path)
    {
        $this->typhoon->readTyphoon(func_get_args());

        $typhoonPath = sprintf(
            '%s/typhoon.json',
            $path
        );
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
        $this->typhoon->readComposer(func_get_args());

        $composerPath = sprintf(
            '%s/composer.json',
            $path
        );
        if (!$this->isolator->is_file($composerPath)) {
            return null;
        }

        $data = $this->loadJSON($composerPath);
        if (
            !$data instanceof stdClass ||
            !property_exists($data, 'extra') ||
            !$data->extra instanceof stdClass ||
            !property_exists($data->extra, 'typhoon')
        ) {
            return null;
        }

        $typhoonData = $data->extra->typhoon;
        if (!property_exists($typhoonData, 'outputPath')) {
            throw new Exception\InvalidConfigurationException(
                "'outputPath' is required."
            );
        }
        if (property_exists($typhoonData, 'sourcePaths')) {
            return $this->buildConfiguration($typhoonData);
        }

        if (
            property_exists($data, 'autoload') &&
            $data->autoload instanceof stdClass
        ) {
            // psr-0
            if (
                property_exists($data->autoload, 'psr-0') &&
                $data->autoload->{'psr-0'} instanceof stdClass
            ) {
                foreach ($data->autoload->{'psr-0'} as $sourcePath) {
                    if (is_array($sourcePath)) {
                        foreach ($sourcePath as $subSourcePath) {
                            $sourcePaths[] = $subSourcePath;
                        }
                    } else {
                        $sourcePaths[] = $sourcePath;
                    }
                }
            }

            // classmap
            if (
                property_exists($data->autoload, 'classmap') &&
                is_array($data->autoload->classmap)
            ) {
                foreach ($data->autoload->classmap as $sourcePath) {
                    $sourcePaths[] = $sourcePath;
                }
            }

            // files
            if (
                property_exists($data->autoload, 'files') &&
                is_array($data->autoload->files)
            ) {
                foreach ($data->autoload->files as $sourcePath) {
                    $sourcePaths[] = $sourcePath;
                }
            }
        }

        // include-path
        if (
            property_exists($data, 'include-path') &&
            is_array($data->{'include-path'})
        ) {
            foreach ($data->{'include-path'} as $sourcePath) {
                $sourcePaths[] = $sourcePath;
            }
        }

        $typhoonData->sourcePaths = array();
        foreach ($sourcePaths as $sourcePath) {
            if (!$this->pathIsDescandantOrEqual(
                $path,
                $typhoonData->outputPath,
                $sourcePath
            )) {
                $typhoonData->sourcePaths[] = $sourcePath;
            }
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
        $this->typhoon->buildConfiguration(func_get_args());

        $this->validateData($data);
        $configuration = new Configuration(
            $data->outputPath,
            $data->sourcePaths
        );
        if (property_exists($data, 'loaderPaths')) {
            $configuration->setLoaderPaths($data->loaderPaths);
        }
        if (property_exists($data, 'useNativeCallable')) {
            $configuration->setUseNativeCallable($data->useNativeCallable);
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
        $this->typhoon->loadJSON(func_get_args());

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
        $this->typhoon->load(func_get_args());

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
        $this->typhoon->validateData(func_get_args());

        if (!$data instanceof stdClass) {
            throw new Exception\InvalidConfigurationException(
                'Typhoon configuration data must be an object.'
            );
        }
        foreach ($data as $optionName => $value) {
            ConfigurationOption::instanceByValue($optionName);
        }

        // outputPath
        if (!property_exists($data, 'outputPath')) {
            throw new Exception\InvalidConfigurationException(
                "'outputPath' is required."
            );
        }
        if (!is_string($data->outputPath)) {
            throw new Exception\InvalidConfigurationException(
                "'outputPath' must be a string."
            );
        }

        // sourcePaths
        if (!property_exists($data, 'sourcePaths')) {
            throw new Exception\InvalidConfigurationException(
                "'sourcePaths' is required."
            );
        }
        if (!is_array($data->sourcePaths)) {
            throw new Exception\InvalidConfigurationException(
                "'sourcePaths' must be an array."
            );
        }
        foreach ($data->sourcePaths as $sourcePath) {
            if (!is_string($sourcePath)) {
                throw new Exception\InvalidConfigurationException(
                    "Entries in 'sourcePaths' must be strings."
                );
            }
        }

        // loaderPaths
        if (property_exists($data, 'loaderPaths')) {
            if (!is_array($data->loaderPaths)) {
                throw new Exception\InvalidConfigurationException(
                    "'loaderPaths' must be an array."
                );
            }
            foreach ($data->loaderPaths as $loaderPath) {
                if (!is_string($loaderPath)) {
                    throw new Exception\InvalidConfigurationException(
                        "Entries in 'loaderPaths' must be strings."
                    );
                }
            }
        }

        // useNativeCallable
        if (property_exists($data, 'useNativeCallable')) {
            if (!is_bool($data->useNativeCallable)) {
                throw new Exception\InvalidConfigurationException(
                    "'useNativeCallable' must be a boolean."
                );
            }
        }
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
        $this->typhoon->pathIsDescandantOrEqual(func_get_args());

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
        $this->typhoon->normalizePath(func_get_args());

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
    private $isolator;
    private $typhoon;
}
