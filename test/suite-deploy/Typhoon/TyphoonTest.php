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
use Typhoon\Eloquent\Typhoon\TestFixture\ExampleTyphoon;

class TyphoonTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();

        Liberator::liberateClass(__NAMESPACE__.'\Typhoon')->instances = array();
        if (class_exists('Typhoon\Eloquent\Typhoon\TestFixture\ExampleTyphoon', false)) {
            ExampleTyphoon::$arguments = null;
        }
    }

    public function testGet()
    {
        $className = 'Eloquent\Typhoon\TestFixture\Example';
        $validator = Typhoon::get($className);

        $this->assertInstanceOf(
            'Typhoon\Eloquent\Typhoon\TestFixture\ExampleTyphoon',
            $validator
        );
        $this->assertSame($validator, Typhoon::get($className));
        $this->assertFalse(array_key_exists('validateConstructor', ExampleTyphoon::$arguments));
    }

    public function testGetWithArguments()
    {
        $className = 'Eloquent\Typhoon\TestFixture\Example';
        $arguments = array(new stdClass);
        $validator = Typhoon::get($className, $arguments);

        $this->assertInstanceOf(
            'Typhoon\Eloquent\Typhoon\TestFixture\ExampleTyphoon',
            $validator
        );
        $this->assertSame($validator, Typhoon::get($className));
        $this->assertTrue(array_key_exists('validateConstructor', ExampleTyphoon::$arguments));
        $this->assertSame($arguments, ExampleTyphoon::$arguments['validateConstructor']);
    }

    public function testValidate()
    {
        $methodName = 'Eloquent\Typhoon\TestFixture\Example::foo';
        $arguments = array(new stdClass);
        $validator = Typhoon::validate($methodName, $arguments);

        $this->assertTrue(array_key_exists('foo', ExampleTyphoon::$arguments));
        $this->assertSame($arguments, ExampleTyphoon::$arguments['foo']);
    }

    public function testInstall()
    {
        $className = 'Eloquent\Typhoon\TestFixture\Example';
        Typhoon::get($className);
        $validator = new stdClass;
        Typhoon::install($className, $validator);

        $this->assertSame($validator, Typhoon::get($className));
    }
}
