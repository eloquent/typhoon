<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Typhoon;

use Eloquent\Liberator\Liberator;
use PHPUnit_Framework_TestCase;
use stdClass;
use Typhoon\Validator\Eloquent\Typhoon\TestFixture\ExampleClassTyphoon;

class TyphoonTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();

        Liberator::liberateClass(__NAMESPACE__.'\Typhoon')->instances = array();
        Liberator::liberateClass(__NAMESPACE__.'\Typhoon')->dummyMode = false;
        if (class_exists('Typhoon\Validator\Eloquent\Typhoon\TestFixture\ExampleClassTyphoon', false)) {
            ExampleClassTyphoon::$arguments = array();
        }

        $this->_oldRuntimeGeneration = Typhoon::runtimeGeneration();
        Typhoon::setRuntimeGeneration(false);
    }

    protected function tearDown()
    {
        parent::tearDown();

        Typhoon::setRuntimeGeneration($this->_oldRuntimeGeneration);
    }

    public function testGet()
    {
        $className = 'Eloquent\Typhoon\TestFixture\ExampleClass';
        $validator = Typhoon::get($className);

        $this->assertInstanceOf(
            'Typhoon\Validator\Eloquent\Typhoon\TestFixture\ExampleClassTyphoon',
            $validator
        );
        $this->assertSame($validator, Typhoon::get($className));
        $this->assertFalse(array_key_exists('validateConstruct', ExampleClassTyphoon::$arguments));
    }

    public function testGetWithArguments()
    {
        $className = 'Eloquent\Typhoon\TestFixture\ExampleClass';
        $arguments = array(new stdClass);
        $validator = Typhoon::get($className, $arguments);

        $this->assertInstanceOf(
            'Typhoon\Validator\Eloquent\Typhoon\TestFixture\ExampleClassTyphoon',
            $validator
        );
        $this->assertSame($validator, Typhoon::get($className));
        $this->assertTrue(array_key_exists('validateConstruct', ExampleClassTyphoon::$arguments));
        $this->assertSame($arguments, ExampleClassTyphoon::$arguments['validateConstruct']);
    }

    public function testGetDummyMode()
    {
        Liberator::liberateClass(__NAMESPACE__.'\Typhoon')->dummyMode = true;

        $this->assertInstanceOf(
            __NAMESPACE__.'\DummyValidator',
            Typhoon::get('foo')
        );
    }

    public function testRuntimeGeneration()
    {
        Typhoon::setRuntimeGeneration(true);
        $className = 'Eloquent\Typhoon\TestFixture\ExampleClassRuntime';
        $validator = Typhoon::get($className);

        $this->assertInstanceOf(
            'Typhoon\Validator\Eloquent\Typhoon\TestFixture\ExampleClassRuntimeTyphoon',
            $validator
        );
        $this->assertSame($validator, Typhoon::get($className));
    }

    public function testInstall()
    {
        $className = 'Eloquent\Typhoon\TestFixture\ExampleClass';
        Typhoon::get($className);
        $validator = new stdClass;
        Typhoon::install($className, $validator);

        $this->assertSame($validator, Typhoon::get($className));
    }
}
