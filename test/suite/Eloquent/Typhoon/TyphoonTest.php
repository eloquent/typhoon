<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon;

use PHPUnit_Framework_TestCase;
use stdClass;

class TyphoonTest extends PHPUnit_Framework_TestCase
{
    public function testGet()
    {
        $className = __NAMESPACE__.'\TestFixture\Example';
        $validator = Typhoon::get($className);

        $this->assertInstanceOf(
            'Typhoon\\'.__NAMESPACE__.'\TestFixture\ExampleTyphoon',
            $validator
        );
        $this->assertSame($validator, Typhoon::get($className));
        $this->assertNull($validator->arguments());
    }

    public function testGetWithArguments()
    {
        $className = __NAMESPACE__.'\TestFixture\Example';
        $arguments = array(new stdClass);
        $validator = Typhoon::get($className, $arguments);

        $this->assertInstanceOf(
            'Typhoon\\'.__NAMESPACE__.'\TestFixture\ExampleTyphoon',
            $validator
        );
        $this->assertSame($validator, Typhoon::get($className));
        $this->assertSame($arguments, $validator->arguments());
    }

    public function testInstall()
    {
        $className = __NAMESPACE__.'\TestFixture\Example';
        Typhoon::get($className);
        $validator = new stdClass;
        Typhoon::install($className, $validator);

        $this->assertSame($validator, Typhoon::get($className));
    }
}
