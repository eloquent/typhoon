<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Compiler;

use Eloquent\Typhax\Type\FloatType;
use Eloquent\Typhax\Type\IntegerType;
use Eloquent\Typhax\Type\StringType;
use Eloquent\Typhoon\Parameter\Parameter;
use Eloquent\Typhoon\Parameter\ParameterList;
use PHPUnit_Framework_TestCase;

class ParameterListCompilerTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->_compiler = new ParameterListCompiler;
    }

    public function testVisitParameter()
    {
        $parameter = new Parameter(
            new StringType,
            'foo'
        );
        $expected = <<<'EOD'
function($argument, $index) {
    $check = function($value) {
        return is_string($value);
    };
    if (!$check($argument)) {
        throw new \InvalidArgumentException("Unexpected argument for parameter 'foo' at index ".$index.".");
    }
}
EOD;

        $this->assertSame($expected, $parameter->accept($this->_compiler));
    }

    public function testVisitParameterList()
    {
        $list = new ParameterList(array(
            new Parameter(
                new StringType,
                'foo'
            ),
            new Parameter(
                new IntegerType,
                'bar'
            ),
            new Parameter(
                new FloatType,
                'baz'
            ),
        ));
        $expected = <<<'EOD'
function(array $arguments) {
    $argumentCount = count($arguments);
    if ($argumentCount < 3) {
        if ($argumentCount < 1) {
            throw new \InvalidArgumentException("Missing argument for parameter 'foo'.");
        }
        if ($argumentCount < 2) {
            throw new \InvalidArgumentException("Missing argument for parameter 'bar'.");
        }
        throw new \InvalidArgumentException("Missing argument for parameter 'baz'.");
    } elseif ($argumentCount > 3) {
        throw new \InvalidArgumentException("Unexpected argument at index 4.");
    }

    $check = function($argument, $index) {
        $check = function($value) {
            return is_string($value);
        };
        if (!$check($argument)) {
            throw new \InvalidArgumentException("Unexpected argument for parameter 'foo' at index ".$index.".");
        }
    };
    $check($arguments[0], 0);

    $check = function($argument, $index) {
        $check = function($value) {
            return is_integer($value);
        };
        if (!$check($argument)) {
            throw new \InvalidArgumentException("Unexpected argument for parameter 'bar' at index ".$index.".");
        }
    };
    $check($arguments[1], 1);

    $check = function($argument, $index) {
        $check = function($value) {
            return is_float($value);
        };
        if (!$check($argument)) {
            throw new \InvalidArgumentException("Unexpected argument for parameter 'baz' at index ".$index.".");
        }
    };
    $check($arguments[2], 2);
}
EOD;

        $this->assertSame($expected, $list->accept($this->_compiler));
    }

    public function testVisitParameterListEmpty()
    {
        $list = new ParameterList;
        $expected = <<<'EOD'
function(array $arguments) {
    if (count($arguments) > 0) {
        throw new \InvalidArgumentException("Unexpected argument at index 1.");
    }
}
EOD;

        $this->assertSame($expected, $list->accept($this->_compiler));
    }

    public function testVisitParameterListVariableLength()
    {
        $list = new ParameterList(
            array(
                new Parameter(
                    new StringType,
                    'foo'
                ),
                new Parameter(
                    new IntegerType,
                    'bar'
                ),
                new Parameter(
                    new FloatType,
                    'baz'
                ),
            ),
            true
        );
        $expected = <<<'EOD'
function(array $arguments) {
    $argumentCount = count($arguments);
    if ($argumentCount < 2) {
        if ($argumentCount < 1) {
            throw new \InvalidArgumentException("Missing argument for parameter 'foo'.");
        }
        throw new \InvalidArgumentException("Missing argument for parameter 'bar'.");
    }

    $check = function($argument, $index) {
        $check = function($value) {
            return is_string($value);
        };
        if (!$check($argument)) {
            throw new \InvalidArgumentException("Unexpected argument for parameter 'foo' at index ".$index.".");
        }
    };
    $check($arguments[0], 0);

    $check = function($argument, $index) {
        $check = function($value) {
            return is_integer($value);
        };
        if (!$check($argument)) {
            throw new \InvalidArgumentException("Unexpected argument for parameter 'bar' at index ".$index.".");
        }
    };
    $check($arguments[1], 1);

    $check = function($argument, $index) {
        $check = function($value) {
            return is_float($value);
        };
        if (!$check($argument)) {
            throw new \InvalidArgumentException("Unexpected argument for parameter 'baz' at index ".$index.".");
        }
    };
    for ($i = 2; $i < $argumentCount; $i ++) {
        $check($arguments[$i], $i);
    }
}
EOD;

        $this->assertSame($expected, $list->accept($this->_compiler));
    }
}
