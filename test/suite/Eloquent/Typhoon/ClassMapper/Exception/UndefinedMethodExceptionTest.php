<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\ClassMapper\Exception;

use Eloquent\Cosmos\ClassName;
use Eloquent\Typhoon\TestCase\MultiGenerationTestCase;
use Phake;

class UndefinedMethodExceptionTest extends MultiGenerationTestCase
{
    public function testException()
    {
        $className = ClassName::fromString('foo');
        $previous = Phake::mock('Exception');
        $exception = new UndefinedMethodException(
            $className,
            'bar',
            $previous
        );

        $this->assertSame("Undefined method 'foo::bar()'.", $exception->getMessage());
        $this->assertSame($className, $exception->className());
        $this->assertSame('bar', $exception->methodName());
        $this->assertSame(0, $exception->getCode());
        $this->assertSame($previous, $exception->getPrevious());
    }
}
