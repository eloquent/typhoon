<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Typhoon;

use Eloquent\Liberator\Liberator;
use PHPUnit_Framework_TestCase;
use stdClass;
use Typhoon\Validator\Eloquent\Typhoon\TestFixture\ExampleClassTypeCheck;

class TypeCheckTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();

        Liberator::liberateClass(__NAMESPACE__.'\TypeCheck')->instances = array();
        Liberator::liberateClass(__NAMESPACE__.'\TypeCheck')->dummyMode = false;
        if (class_exists('Typhoon\Validator\Eloquent\Typhoon\TestFixture\ExampleClassTypeCheck', false)) {
            ExampleClassTypeCheck::$arguments = array();
        }

        $this->_oldDummyMode = TypeCheck::dummyMode();
        $this->_oldRuntimeGeneration = TypeCheck::runtimeGeneration();
        TypeCheck::setDummyMode(false);
        TypeCheck::setRuntimeGeneration(false);
    }

    protected function tearDown()
    {
        parent::tearDown();

        TypeCheck::setDummyMode($this->_oldDummyMode);
        TypeCheck::setRuntimeGeneration($this->_oldRuntimeGeneration);
    }

    public function testGet()
    {
        $className = 'Eloquent\Typhoon\TestFixture\ExampleClass';
        $validator = TypeCheck::get($className);

        $this->assertInstanceOf(
            'Typhoon\Validator\Eloquent\Typhoon\TestFixture\ExampleClassTypeCheck',
            $validator
        );
        $this->assertSame($validator, TypeCheck::get($className));
        $this->assertFalse(array_key_exists('validateConstruct', ExampleClassTypeCheck::$arguments));
    }

    public function testGetWithArguments()
    {
        $className = 'Eloquent\Typhoon\TestFixture\ExampleClass';
        $arguments = array(new stdClass);
        $validator = TypeCheck::get($className, $arguments);

        $this->assertInstanceOf(
            'Typhoon\Validator\Eloquent\Typhoon\TestFixture\ExampleClassTypeCheck',
            $validator
        );
        $this->assertSame($validator, TypeCheck::get($className));
        $this->assertTrue(array_key_exists('validateConstruct', ExampleClassTypeCheck::$arguments));
        $this->assertSame($arguments, ExampleClassTypeCheck::$arguments['validateConstruct']);
    }

    public function testDummyMode()
    {
        $this->assertFalse(TypeCheck::dummyMode());

        TypeCheck::setDummyMode(true);

        $this->assertTrue(TypeCheck::dummyMode());
    }

    public function testGetDummyValidator()
    {
        TypeCheck::setDummyMode(true);

        $this->assertInstanceOf(
            __NAMESPACE__.'\DummyValidator',
            TypeCheck::get('foo')
        );
    }

    public function testRuntimeGeneration()
    {
        TypeCheck::setRuntimeGeneration(true);
        $className = 'Eloquent\Typhoon\TestFixture\ExampleClassRuntime';
        $validator = TypeCheck::get($className);

        $this->assertInstanceOf(
            'Typhoon\Validator\Eloquent\Typhoon\TestFixture\ExampleClassRuntimeTypeCheck',
            $validator
        );
        $this->assertSame($validator, TypeCheck::get($className));
    }

    public function testInstall()
    {
        $className = 'Eloquent\Typhoon\TestFixture\ExampleClass';
        TypeCheck::get($className);
        $validator = new stdClass;
        TypeCheck::install($className, $validator);

        $this->assertSame($validator, TypeCheck::get($className));
    }
}
