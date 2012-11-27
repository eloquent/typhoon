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

class Configuration extends RuntimeConfiguration
{
    /**
     * @param string        $outputPath
     * @param array<string> $sourcePaths
     */
    public function __construct(
        $outputPath,
        array $sourcePaths
    ) {
        $this->typhoon = Typhoon::get(__CLASS__, func_get_args());

        parent::__construct();

        $this->setOutputPath($outputPath);
        $this->setSourcePaths($sourcePaths);
        $this->loaderPaths = array('vendor/autoload.php');
    }

    /**
     * @param string $outputPath
     */
    public function setOutputPath($outputPath)
    {
        $this->typhoon->setOutputPath(func_get_args());

        $this->outputPath = $outputPath;
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
     * @param array<string> $sourcePaths
     */
    public function setSourcePaths(array $sourcePaths)
    {
        $this->typhoon->setSourcePaths(func_get_args());

        if (count($sourcePaths) < 1) {
            throw new Exception\InvalidConfigurationException(
                "'sourcePaths' must not be empty."
            );
        }
        $this->sourcePaths = $sourcePaths;
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
     * @param array<string> $loaderPaths
     */
    public function setLoaderPaths(array $loaderPaths)
    {
        $this->typhoon->setLoaderPaths(func_get_args());

        $this->loaderPaths = $loaderPaths;
    }

    /**
     * @return array<string>
     */
    public function loaderPaths()
    {
        $this->typhoon->loaderPaths(func_get_args());

        return $this->loaderPaths;
    }

    private $outputPath;
    private $sourcePaths;
    private $loaderPaths;
    private $typhoon;
}
