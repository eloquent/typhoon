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
use Typhoon\Typhoon;

class ConfigurationReader
{
    /**
     * @param Isolator|null $isolator
     */
    public function __construct(Isolator $isolator = null)
    {
        $this->typhoon = Typhoon::get(__CLASS__, func_get_args());
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

        $data = array_merge(
            $this->readComposer($path),
            $this->readTyphoon($path)
        );
        $this->finalizeConfigurationData($data);

        return new Configuration(
            $data['outputPath'],
            $data['sourcePaths'],
            $data['loaderPaths'],
            $data['useNativeCallable']
        );
    }

    /**
     * @param string $path
     *
     * @return array<string,mixed>
     */
    protected function readComposer($path)
    {
        $this->typhoon->readComposer(func_get_args());

        $composerPath = sprintf(
            '%s/composer.json',
            $path
        );
        if (!$this->isolator->is_file($composerPath)) {
            return array();
        }

        $data = $this->loadJSON($composerPath);
        if (
            !is_array($data) ||
            !array_key_exists('extra', $data) ||
            !is_array($data['extra']) ||
            !array_key_exists('typhoon', $data['extra'])
        ) {
            return array();
        }

        $typhoonData = $data['extra']['typhoon'];
        if (!array_key_exists('outputPath', $typhoonData)) {
            throw new Exception\InvalidConfigurationException(
                "'outputPath' is required."
            );
        }
        if (array_key_exists('sourcePaths', $typhoonData)) {
            return $typhoonData;
        }

        $typhoonData['sourcePaths'] = array();

        if (
            array_key_exists('autoload', $data) &&
            is_array($data['autoload'])
        ) {
            // psr-0
            if (
                array_key_exists('psr-0', $data['autoload']) &&
                is_array($data['autoload']['psr-0'])
            ) {
                foreach ($data['autoload']['psr-0'] as $sourcePath) {
                    if (is_array($sourcePath)) {
                        foreach ($sourcePath as $subSourcePath) {
                            $typhoonData['sourcePaths'][] = $subSourcePath;
                        }
                    } else {
                        $typhoonData['sourcePaths'][] = $sourcePath;
                    }
                }
            }

            // classmap
            if (
                array_key_exists('classmap', $data['autoload']) &&
                is_array($data['autoload']['classmap'])
            ) {
                foreach ($data['autoload']['classmap'] as $sourcePath) {
                    $sourcePaths[] = $sourcePath;
                }
            }

            // files
            if (
                array_key_exists('files', $data['autoload']) &&
                is_array($data['autoload']['files'])
            ) {
                foreach ($data['autoload']['files'] as $sourcePath) {
                    $sourcePaths[] = $sourcePath;
                }
            }
        }

        // include-path
        if (
            array_key_exists('include-path', $data) &&
            is_array($data['include-path'])
        ) {
            foreach ($data['include-path'] as $sourcePath) {
                $typhoonData['sourcePaths'][] = $sourcePath;
            }
        }

        return $typhoonData;
    }

    /**
     * @param string $path
     *
     * @return array<string,mixed>
     */
    protected function readTyphoon($path)
    {
        $this->typhoon->readTyphoon(func_get_args());

        $typhoonPath = sprintf(
            '%s/typhoon.json',
            $path
        );
        if (!$this->isolator->is_file($typhoonPath)) {
            return array();
        }

        return $this->loadJSON($typhoonPath);
    }

    /**
     * @param string $path
     *
     * @return mixed
     */
    protected function loadJSON($path)
    {
        $this->typhoon->loadJSON(func_get_args());

        $data = json_decode($this->load($path), true);
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
     * @param mixed &$data
     */
    protected function finalizeConfigurationData(&$data)
    {
        $this->typhoon->finalizeConfigurationData(func_get_args());

        if (!is_array($data)) {
            throw new Exception\InvalidConfigurationException(
                'Invalid type for Typhoon configuration data.'
            );
        }
        foreach ($data as $optionName => $value) {
            ConfigurationOption::instanceByValue($optionName);
        }

        // outputPath
        if (!array_key_exists('outputPath', $data)) {
            throw new Exception\InvalidConfigurationException(
                "'outputPath' is required."
            );
        }
        if (!is_string($data['outputPath'])) {
            throw new Exception\InvalidConfigurationException(
                "'outputPath' must be a string."
            );
        }

        // sourcePaths
        if (!array_key_exists('sourcePaths', $data)) {
            throw new Exception\InvalidConfigurationException(
                "'sourcePaths' is required."
            );
        }
        if (!is_array($data['sourcePaths'])) {
            throw new Exception\InvalidConfigurationException(
                "'sourcePaths' must be an array."
            );
        }
        foreach ($data['sourcePaths'] as $sourcePath) {
            if (!is_string($sourcePath)) {
                throw new Exception\InvalidConfigurationException(
                    "Entries in 'sourcePaths' must be strings."
                );
            }
        }

        // loaderPaths
        if (array_key_exists('loaderPaths', $data)) {
            if (!is_array($data['loaderPaths'])) {
                throw new Exception\InvalidConfigurationException(
                    "'loaderPaths' must be an array."
                );
            }
            foreach ($data['loaderPaths'] as $loaderPath) {
                if (!is_string($loaderPath)) {
                    throw new Exception\InvalidConfigurationException(
                        "Entries in 'loaderPaths' must be strings."
                    );
                }
            }
        } else {
            $data['loaderPaths'] = array('vendor/autoload.php');
        }

        // useNativeCallable
        if (array_key_exists('useNativeCallable', $data)) {
            if (!is_bool($data['useNativeCallable'])) {
                throw new Exception\InvalidConfigurationException(
                    "'useNativeCallable' must be a boolean."
                );
            }
        } else {
            $data['useNativeCallable'] = true;
        }
    }

    private $isolator;
    private $typhoon;
}
