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
use Typhoon\Eloquent\Typhoon\TestFixture\ExampleClassTyphoon;

class TyphoonTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();

        Liberator::liberateClass(__NAMESPACE__.'\Typhoon')->instances = array();
        if (class_exists('Typhoon\Eloquent\Typhoon\TestFixture\ExampleClassTyphoon', false)) {
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
            'Typhoon\Eloquent\Typhoon\TestFixture\ExampleClassTyphoon',
            $validator
        );
        $this->assertSame($validator, Typhoon::get($className));
        $this->assertFalse(array_key_exists('validateConstructor', ExampleClassTyphoon::$arguments));
    }

    public function testGetWithArguments()
    {
        $className = 'Eloquent\Typhoon\TestFixture\ExampleClass';
        $arguments = array(new stdClass);
        $validator = Typhoon::get($className, $arguments);

        $this->assertInstanceOf(
            'Typhoon\Eloquent\Typhoon\TestFixture\ExampleClassTyphoon',
            $validator
        );
        $this->assertSame($validator, Typhoon::get($className));
        $this->assertTrue(array_key_exists('validateConstructor', ExampleClassTyphoon::$arguments));
        $this->assertSame($arguments, ExampleClassTyphoon::$arguments['validateConstructor']);
    }

    public function testRuntimeGeneration()
    {
        Typhoon::setRuntimeGeneration(true);
        $className = 'Eloquent\Typhoon\TestFixture\ExampleClassRuntime';
        $validator = Typhoon::get($className);

        $this->assertInstanceOf(
            'Typhoon\Eloquent\Typhoon\TestFixture\ExampleClassRuntimeTyphoon',
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
