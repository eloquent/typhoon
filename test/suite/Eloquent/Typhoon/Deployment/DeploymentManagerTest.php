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

use Eloquent\Typhoon\TestCase\MultiGenerationTestCase;
use Phake;

class DeploymentManagerTest extends MultiGenerationTestCase
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
        $isDirVerificationTyphoon = Phake::verify($isolator, Phake::times(3))
            ->is_dir('foo/Typhoon')
        ;
        $isDirVerificationException = Phake::verify($isolator, Phake::times(4))
            ->is_dir('foo/Typhoon/Exception')
        ;
        Phake::inOrder(
            $isDirVerificationTyphoon,
            Phake::verify($isolator)->copy(
                $this->_deploySourcePath.'/Typhoon/DummyValidator.php',
                'foo/Typhoon/DummyValidator.php'
            ),
            $isDirVerificationTyphoon,
            Phake::verify($isolator)->copy(
                $this->_deploySourcePath.'/Typhoon/TypeInspector.php',
                'foo/Typhoon/TypeInspector.php'
            ),
            $isDirVerificationTyphoon,
            Phake::verify($isolator)->copy(
                $this->_deploySourcePath.'/Typhoon/Validator.php',
                'foo/Typhoon/Validator.php'
            ),
            $isDirVerificationException,
            Phake::verify($isolator)->copy(
                $this->_deploySourcePath.'/Typhoon/Exception/MissingArgumentException.php',
                'foo/Typhoon/Exception/MissingArgumentException.php'
            ),
            $isDirVerificationException,
            Phake::verify($isolator)->copy(
                $this->_deploySourcePath.'/Typhoon/Exception/UnexpectedArgumentException.php',
                'foo/Typhoon/Exception/UnexpectedArgumentException.php'
            ),
            $isDirVerificationException,
            Phake::verify($isolator)->copy(
                $this->_deploySourcePath.'/Typhoon/Exception/UnexpectedArgumentValueException.php',
                'foo/Typhoon/Exception/UnexpectedArgumentValueException.php'
            ),
            $isDirVerificationException,
            Phake::verify($isolator)->copy(
                $this->_deploySourcePath.'/Typhoon/Exception/UnexpectedInputException.php',
                'foo/Typhoon/Exception/UnexpectedInputException.php'
            )
        );
    }

    public function testDeployCopyCreateDir()
    {
        $isolator = Phake::mock('Icecave\Isolator\Isolator');
        Phake::when($isolator)->is_dir(Phake::anyParameters())
            ->thenReturn(false)
            ->thenReturn(true)
        ;
        $manager = new DeploymentManager($isolator);

        $manager->deploy('foo');
        $isDirVerificationTyphoon = Phake::verify($isolator, Phake::times(3))
            ->is_dir('foo/Typhoon')
        ;
        Phake::inOrder(
            $isDirVerificationTyphoon,
            Phake::verify($isolator)->mkdir('foo/Typhoon', 0777, true),
            Phake::verify($isolator)->copy(
                $this->_deploySourcePath.'/Typhoon/DummyValidator.php',
                'foo/Typhoon/DummyValidator.php'
            ),
            $isDirVerificationTyphoon
        );
    }
}
