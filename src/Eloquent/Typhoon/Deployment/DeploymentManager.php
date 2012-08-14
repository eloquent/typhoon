<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Deployment;

use Icecave\Isolator\Isolator;
use Typhoon\Typhoon;

class DeploymentManager
{
    /**
     * @param Isolator|null $isolator
     */
    public function __construct(Isolator $isolator = null)
    {
        $this->typhoon = Typhoon::get(__CLASS__, func_get_args());
        $this->deploySourcePath =
            dirname(dirname(dirname(dirname(__DIR__)))).
            '/src-deploy'
        ;
        $this->isolator = Isolator::get($isolator);
    }

    /**
     * @param string $path
     */
    public function deploy($path)
    {
        $this->typhoon->deploy(func_get_args());

        $this->copyFile(
            $this->deploySourcePath.'/Typhoon/Typhoon.php',
            $path.'/Typhoon/Typhoon.php'
        );
        $this->copyFile(
            $this->deploySourcePath.'/Typhoon/DummyValidator.php',
            $path.'/Typhoon/DummyValidator.php'
        );
        $this->copyFile(
            $this->deploySourcePath.'/Typhoon/Exception/MissingArgumentException.php',
            $path.'/Typhoon/Exception/MissingArgumentException.php'
        );
        $this->copyFile(
            $this->deploySourcePath.'/Typhoon/Exception/UnexpectedArgumentException.php',
            $path.'/Typhoon/Exception/UnexpectedArgumentException.php'
        );
        $this->copyFile(
            $this->deploySourcePath.'/Typhoon/Exception/UnexpectedArgumentValueException.php',
            $path.'/Typhoon/Exception/UnexpectedArgumentValueException.php'
        );
        $this->copyFile(
            $this->deploySourcePath.'/Typhoon/Exception/UnexpectedInputException.php',
            $path.'/Typhoon/Exception/UnexpectedInputException.php'
        );
    }

    /**
     * @param string $from
     * @param string $to
     */
    protected function copyFile($from, $to)
    {
        $this->typhoon->copyFile(func_get_args());

        $parentPath = dirname($to);
        if (!$this->isolator->is_dir($parentPath)) {
            $this->isolator->mkdir($parentPath, 0777, true);
        }

        $this->isolator->copy($from, $to);
    }

    private $deploySourcePath;
    private $isolator;
    private $typhoon;
}
