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

use Phake;
use PHPUnit_Framework_TestCase;

class DeploymentManagerTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->_deploySourcePath =
            dirname(dirname(dirname(dirname(dirname(__DIR__))))).
            '/src-deploy'
        ;
    }

    public function testDeploy()
    {
        $isolator = Phake::mock('Icecave\Isolator\Isolator');
        Phake::when($isolator)->is_dir(Phake::anyParameters())->thenReturn(true);
        $manager = new DeploymentManager($isolator);


        $manager->deploy('foo');
        Phake::inOrder(
            Phake::verify($isolator)->is_dir('foo/Typhoon'),
            Phake::verify($isolator)->copy(
                $this->_deploySourcePath.'/Typhoon/Typhoon.php',
                'foo/Typhoon/Typhoon.php'
            )
        );
    }

    public function testDeployCopyCreateDir()
    {
        $isolator = Phake::mock('Icecave\Isolator\Isolator');
        Phake::when($isolator)->is_dir(Phake::anyParameters())->thenReturn(false);
        $manager = new DeploymentManager($isolator);


        $manager->deploy('foo');
        Phake::inOrder(
            Phake::verify($isolator)->is_dir('foo/Typhoon'),
            Phake::verify($isolator)->mkdir('foo/Typhoon', 0777, true),
            Phake::verify($isolator)->copy(
                $this->_deploySourcePath.'/Typhoon/Typhoon.php',
                'foo/Typhoon/Typhoon.php'
            )
        );
    }
}
