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

class DeploymentManager
{
    /**
     * @param Isolator|null $isolator
     */
    public function __construct(Isolator $isolator = null)
    {
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
        $this->copyFile(
            $this->deploySourcePath.'/Typhoon/Typhoon.php',
            $path.'/Typhoon/Typhoon.php'
        );
    }

    /**
     * @param string $from
     * @param string $to
     */
    protected function copyFile($from, $to)
    {
        $parentPath = dirname($to);
        if (!$this->isolator->is_dir($parentPath)) {
            $this->isolator->mkdir($parentPath, 0777, true);
        }

        $this->isolator->copy($from, $to);
    }

    private $deploySourcePath;
    private $isolator;
}
