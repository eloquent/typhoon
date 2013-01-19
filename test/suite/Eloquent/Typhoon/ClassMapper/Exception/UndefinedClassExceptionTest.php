<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\ClassMapper\Exception;

use Eloquent\Cosmos\ClassName;
use Eloquent\Typhoon\TestCase\MultiGenerationTestCase;
use Phake;

class UndefinedClassExceptionTest extends MultiGenerationTestCase
{
    public function testException()
    {
        $className = ClassName::fromString('foo');
        $previous = Phake::mock('Exception');
        $exception = new UndefinedClassException(
            $className,
            $previous
        );

        $this->assertSame($className, $exception->className());
        $this->assertSame($previous, $exception->getPrevious());
    }
}
