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
use ReflectionObject;
use stdClass;
use Typhoon\Typhoon;

class ConfigurationLoader
{
    /**
     * @param ConfigurationValidator|null $validator
     * @param Isolator|null $isolator
     */
    public function __construct(
        ConfigurationValidator $validator = null,
        Isolator $isolator = null
    ) {
        $this->typhoon = Typhoon::get(__CLASS__, func_get_args());
        if (null === $validator) {
            $validator = new ConfigurationValidator;
        }

        $this->validator = $validator;
        $this->isolator = Isolator::get($isolator);
    }

    /**
     * @return ConfigurationValidator
     */
    public function validator()
    {
        $this->typhoon->validator(func_get_args());

        return $this->validator;
    }

    /**
     * @param string|null $workingPath
     *
     * @return Configuration|null
     */
    public function load($workingPath = null)
    {
        $this->typhoon->load(func_get_args());

        $standalonePath = sprintf(
            '%s/typhoon.json',
            $workingPath
        );
        if ($this->isolator->is_file($standalonePath)) {
            return $this->loadStandalone($standalonePath, $workingPath);
        }

        $composerPath = sprintf(
            '%s/composer.json',
            $workingPath
        );
        if ($this->isolator->is_file($composerPath)) {
            return $this->loadComposer($composerPath, $workingPath);
        }

        return null;
    }

    /**
     * @param string $path
     * @param string|null $workingPath
     *
     * @return Configuration
     */
    public function loadStandalone($path, $workingPath = null)
    {
        $this->typhoon->loadStandalone(func_get_args());

        return $this->build(
            $this->loadJSONFile($path),
            $workingPath
        );
    }

    /**
     * @param string $path
     * @param string|null $workingPath
     *
     * @return Configuration|null
     */
    public function loadComposer($path, $workingPath = null)
    {
        $this->typhoon->loadComposer(func_get_args());

        $configuration = $this->loadJSONFile($path);
        if (
            !$configuration instanceof stdClass ||
            !$this->objectHasProperty($configuration, 'extra') ||
            !$configuration->extra instanceof stdClass ||
            !$this->objectHasProperty($configuration->extra, 'typhoon')
        ) {
            return null;
        }

        return $this->build($configuration->extra->typhoon, $workingPath);
    }

    /**
     * @param string $path
     *
     * @return mixed
     */
    protected function loadJSONFile($path)
    {
        try {
            $json = $this->isolator->file_get_contents($path);
        } catch (ErrorException $e) {
            throw new Exception\ConfigurationReadException($path, $e);
        }

        $data = $this->isolator->json_decode($json);
        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new Exception\ConfigurationReadException($path);
        }

        return $data;
    }

    /**
     * @param mixed $data
     * @param string|null $workingPath
     *
     * @return Configuration
     */
    protected function build($data, $workingPath = null)
    {
        $this->typhoon->build(func_get_args());
        if (null === $workingPath) {
            $workingPath = $this->isolator->getcwd();
        }

        $this->validator()->validate($data);

        // loaderPaths
        if ($this->objectHasProperty($data, 'loaderPaths')) {
            $loaderPaths = $data->loaderPaths;
        } else {
            $loaderPaths = $this->defaultLoaderPaths($workingPath);
        }

        // useNativeCallable
        if ($this->objectHasProperty($data, 'useNativeCallable')) {
            $useNativeCallable = $data->useNativeCallable;
        } else {
            $useNativeCallable = true;
        }

        return new Configuration(
            $data->outputPath,
            $data->sourcePaths,
            $loaderPaths,
            $useNativeCallable
        );
    }

    /**
     * @param string $workingPath
     *
     * @return array<string>
     */
    protected function defaultLoaderPaths($workingPath)
    {
        $this->typhoon->defaultLoaderPaths(func_get_args());

        return array(sprintf(
            '%s/vendor/autoload.php',
            $workingPath
        ));
    }

    /**
     * @param stdClass $object
     * @param string $property
     *
     * @return boolean
     */
    protected function objectHasProperty(stdClass $object, $property)
    {
        $this->typhoon->objectHasProperty(func_get_args());
        $reflector = new ReflectionObject($object);

        return $reflector->hasProperty($property);
    }

    private $validator;
    private $isolator;
    private $typhoon;
}
