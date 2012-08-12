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

use PHPUnit_Framework_TestCase;
use stdClass;

class TyphoonTest extends PHPUnit_Framework_TestCase
{
    public function testGet()
    {
        $className = 'Eloquent\Typhoon\TestFixture\Example';
        $validator = Typhoon::get($className);

        $this->assertInstanceOf(
            'Typhoon\Eloquent\Typhoon\TestFixture\ExampleTyphoon',
            $validator
        );
        $this->assertSame($validator, Typhoon::get($className));
        $this->assertNull($validator->arguments());
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
        $this->assertSame($arguments, $validator->arguments());
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
