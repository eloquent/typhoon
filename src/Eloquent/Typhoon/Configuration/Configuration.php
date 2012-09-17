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

use Typhoon\Typhoon;

class Configuration
{
    /**
     * @param string $outputPath
     * @param array<string> $sourcePaths
     * @param array<string> $loaderPaths
     * @param boolean $useNativeCallable
     */
    public function __construct(
        $outputPath,
        array $sourcePaths,
        array $loaderPaths,
        $useNativeCallable
    ) {
        $this->typhoon = Typhoon::get(__CLASS__, func_get_args());

        if (count($sourcePaths) < 1) {
            throw new Exception\InvalidConfigurationException(
                "'sourcePaths' must not be empty."
            );
        }

        $this->outputPath = $outputPath;
        $this->sourcePaths = $sourcePaths;
        $this->loaderPaths = $loaderPaths;
        $this->useNativeCallable = $useNativeCallable;
    }

    /**
     * @return string
     */
    public function outputPath()
    {
        $this->typhoon->outputPath(func_get_args());

        return $this->outputPath;
    }

    /**
     * @return array<string>
     */
    public function sourcePaths()
    {
        $this->typhoon->sourcePaths(func_get_args());

        return $this->sourcePaths;
    }

    /**
     * @return array<string>
     */
    public function loaderPaths()
    {
        $this->typhoon->loaderPaths(func_get_args());

        return $this->loaderPaths;
    }

    /**
     * @return boolean
     */
    public function useNativeCallable()
    {
        $this->typhoon->useNativeCallable(func_get_args());

        return $this->useNativeCallable;
    }

    private $outputPath;
    private $sourcePaths;
    private $loaderPaths;
    private $useNativeCallable;
    private $typhoon;
}
