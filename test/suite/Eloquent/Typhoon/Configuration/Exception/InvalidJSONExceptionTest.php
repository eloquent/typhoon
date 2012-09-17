<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Configuration\Exception;

use Eloquent\Typhoon\TestCase\MultiGenerationTestCase;
use Phake;

class InvalidJSONExceptionTest extends MultiGenerationTestCase
{
    public function exceptionData()
    {
        return array(
            array(
                "Invalid JSON in 'foo' - The maximum stack depth has been exceeded.",
                JSON_ERROR_DEPTH,
            ),
            array(
                "Invalid JSON in 'foo' - Invalid or malformed JSON.",
                JSON_ERROR_STATE_MISMATCH,
            ),
            array(
                "Invalid JSON in 'foo' - Control character error, possibly incorrectly encoded.",
                JSON_ERROR_CTRL_CHAR,
            ),
            array(
                "Invalid JSON in 'foo' - Syntax error.",
                JSON_ERROR_SYNTAX,
            ),
            array(
                "Invalid JSON in 'foo' - Malformed UTF-8 characters, possibly incorrectly encoded.",
                JSON_ERROR_UTF8,
            ),
            array(
                "Invalid JSON in 'foo' - An unknown error occurred.",
                JSON_ERROR_NONE,
            ),
        );
    }

    /**
     * @dataProvider exceptionData
     */
    public function testException($expectedMessage, $jsonErrorCode)
    {
        $previous = Phake::mock('Exception');
        $exception = new InvalidJSONException(
            $jsonErrorCode,
            'foo',
            $previous
        );

        $this->assertSame($expectedMessage, $exception->getMessage());
        $this->assertSame($jsonErrorCode, $exception->jsonErrorCode());
        $this->assertSame('foo', $exception->path());
        $this->assertSame(0, $exception->getCode());
        $this->assertSame($previous, $exception->getPrevious());
    }
}
