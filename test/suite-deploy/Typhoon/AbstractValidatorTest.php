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

use Eloquent\Typhoon\TestFixture\ExampleValidator;
use Phake;
use PHPUnit_Framework_TestCase;

class AbstractValidatorTest extends PHPUnit_Framework_TestCase
{
    public function testCall()
    {
        $validator = Phake::partialMock('Eloquent\Typhoon\TestFixture\ExampleValidator');
        Phake::when($validator)->validateFoo(Phake::anyParameters())->thenReturn(null);
        Phake::when($validator)->validateBar(Phake::anyParameters())->thenReturn(null);

        $this->assertNull($validator->foo(array('qux', 'doom')));
        $this->assertNull($validator->__bar(array('splat', 'ping')));
        Phake::inOrder(
            Phake::verify($validator)->validateFoo(array('qux', 'doom')),
            Phake::verify($validator)->validateBar(array('splat', 'ping'))
        );
    }

    public function testCallFailure()
    {
        $validator = new ExampleValidator;

        $this->setExpectedException('BadMethodCallException', 'Call to undefined method Typhoon\AbstractValidator::baz().');
        $validator->baz(array());
    }
}
