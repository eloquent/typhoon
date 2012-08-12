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

    protected function validatorFixture(ParameterList $parameterList)
    {
        eval('$check = function(array $arguments) {'.$parameterList->accept($this->_compiler).'};');

        return $check;
    }

    public function testVisitParameter()
    {
        $parameter = new Parameter(
            'foo',
            new StringType
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
                'foo',
                new StringType
            ),
            new Parameter(
                'bar',
                new IntegerType
            ),
            new Parameter(
                'baz',
                new FloatType
            ),
        ));
        $expected = <<<'EOD'
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
EOD;

        $this->assertSame($expected, $list->accept($this->_compiler));
    }

    public function testVisitParameterListOptionalParameters()
    {
        $list = new ParameterList(array(
            new Parameter(
                'foo',
                new StringType
            ),
            new Parameter(
                'bar',
                new IntegerType,
                true
            ),
            new Parameter(
                'baz',
                new FloatType,
                true
            ),
        ));
        $expected = <<<'EOD'
$argumentCount = count($arguments);
if ($argumentCount < 1) {
    throw new \InvalidArgumentException("Missing argument for parameter 'foo'.");
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

if ($argumentCount > 1) {
    $check = function($argument, $index) {
        $check = function($value) {
            return is_integer($value);
        };
        if (!$check($argument)) {
            throw new \InvalidArgumentException("Unexpected argument for parameter 'bar' at index ".$index.".");
        }
    };
    $check($arguments[1], 1);
}

if ($argumentCount > 2) {
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

    public function testVisitParameterListNoRequiredParameters()
    {
        $list = new ParameterList(array(
            new Parameter(
                'foo',
                new StringType,
                true
            ),
            new Parameter(
                'bar',
                new IntegerType,
                true
            ),
            new Parameter(
                'baz',
                new FloatType,
                true
            ),
        ));
        $expected = <<<'EOD'
$argumentCount = count($arguments);
if ($argumentCount > 3) {
    throw new \InvalidArgumentException("Unexpected argument at index 4.");
}

if ($argumentCount > 0) {
    $check = function($argument, $index) {
        $check = function($value) {
            return is_string($value);
        };
        if (!$check($argument)) {
            throw new \InvalidArgumentException("Unexpected argument for parameter 'foo' at index ".$index.".");
        }
    };
    $check($arguments[0], 0);
}

if ($argumentCount > 1) {
    $check = function($argument, $index) {
        $check = function($value) {
            return is_integer($value);
        };
        if (!$check($argument)) {
            throw new \InvalidArgumentException("Unexpected argument for parameter 'bar' at index ".$index.".");
        }
    };
    $check($arguments[1], 1);
}

if ($argumentCount > 2) {
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

    public function testVisitParameterListVariableLength()
    {
        $list = new ParameterList(
            array(
                new Parameter(
                    'foo',
                    new StringType
                ),
                new Parameter(
                    'bar',
                    new IntegerType
                ),
                new Parameter(
                    'baz',
                    new FloatType,
                    true
                ),
            ),
            true
        );
        $expected = <<<'EOD'
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

if ($argumentCount > 2) {
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

    public function testVisitParameterListUnrestricted()
    {
        $list = ParameterList::createUnrestricted();

        $expected = <<<'EOD'
$argumentCount = count($arguments);

if ($argumentCount > 0) {
    $check = function($argument, $index) {
        $check = function($value) {
            return true;
        };
        if (!$check($argument)) {
            throw new \InvalidArgumentException("Unexpected argument for parameter 'undefined' at index ".$index.".");
        }
    };
    for ($i = 0; $i < $argumentCount; $i ++) {
        $check($arguments[$i], $i);
    }
}
EOD;

        $this->assertSame($expected, $list->accept($this->_compiler));
    }

    public function testVisitParameterListEmpty()
    {
        $list = new ParameterList;
        $expected = <<<'EOD'
if (count($arguments) > 0) {
    throw new \InvalidArgumentException("Unexpected argument at index 1.");
}
EOD;

        $this->assertSame($expected, $list->accept($this->_compiler));
    }

    public function testVisitParameterListLogic()
    {
        $validatorFixture = $this->validatorFixture(new ParameterList(array(
            new Parameter(
                'foo',
                new StringType
            ),
            new Parameter(
                'bar',
                new IntegerType
            ),
            new Parameter(
                'baz',
                new FloatType
            ),
        )));

        $this->assertNull($validatorFixture(array('foo', 111, 1.11)));
        $this->assertNull($validatorFixture(array('bar', 222, 2.22)));
    }

    public function testVisitParameterListLogicOptional()
    {
        $validatorFixture = $this->validatorFixture(new ParameterList(array(
            new Parameter(
                'foo',
                new StringType
            ),
            new Parameter(
                'bar',
                new IntegerType,
                true
            ),
            new Parameter(
                'baz',
                new FloatType,
                true
            ),
        )));

        $this->assertNull($validatorFixture(array('foo')));
        $this->assertNull($validatorFixture(array('bar')));
    }

    public function testVisitParameterListLogicNoRequired()
    {
        $validatorFixture = $this->validatorFixture(new ParameterList(array(
            new Parameter(
                'foo',
                new StringType,
                true
            ),
            new Parameter(
                'bar',
                new IntegerType,
                true
            ),
            new Parameter(
                'baz',
                new FloatType,
                true
            ),
        )));

        $this->assertNull($validatorFixture(array('foo')));
        $this->assertNull($validatorFixture(array()));
    }

    public function testVisitParameterListLogicVariableLength()
    {
        $validatorFixture = $this->validatorFixture(new ParameterList(
            array(
                new Parameter(
                    'foo',
                    new StringType
                ),
                new Parameter(
                    'bar',
                    new IntegerType
                ),
                new Parameter(
                    'baz',
                    new FloatType,
                    true
                ),
            ),
            true
        ));

        $this->assertNull($validatorFixture(array('foo', 111, 1.11, 2.22, 3.33, 4.44, 5.55)));
        $this->assertNull($validatorFixture(array('bar', 222, 6.66, 7.77, 8.88, 9.99)));
        $this->assertNull($validatorFixture(array('baz', 333)));
    }

    public function testVisitParameterListLogicEmpty()
    {
        $validatorFixture = $this->validatorFixture(new ParameterList);

        $this->assertNull($validatorFixture(array()));
        $this->assertNull($validatorFixture(array()));
    }

    public function parameterListLogicFailureData()
    {
        $data = array();

        $list = new ParameterList(array(
            new Parameter(
                'foo',
                new StringType
            ),
            new Parameter(
                'bar',
                new IntegerType
            ),
            new Parameter(
                'baz',
                new FloatType
            ),
        ));
        $arguments = array('foo', 111);
        $expected = 'InvalidArgumentException';
        $expectedMessage = "Missing argument for parameter 'baz'.";
        $data['Not enough arguments 1'] =
            array($expected, $expectedMessage, $list, $arguments)
        ;

        $list = new ParameterList(array(
            new Parameter(
                'foo',
                new StringType
            ),
            new Parameter(
                'bar',
                new IntegerType
            ),
            new Parameter(
                'baz',
                new FloatType
            ),
        ));
        $arguments = array('foo');
        $expected = 'InvalidArgumentException';
        $expectedMessage = "Missing argument for parameter 'bar'.";
        $data['Not enough arguments 2'] =
            array($expected, $expectedMessage, $list, $arguments)
        ;

        $list = new ParameterList(array(
            new Parameter(
                'foo',
                new StringType
            ),
            new Parameter(
                'bar',
                new IntegerType
            ),
            new Parameter(
                'baz',
                new FloatType
            ),
        ));
        $arguments = array();
        $expected = 'InvalidArgumentException';
        $expectedMessage = "Missing argument for parameter 'foo'.";
        $data['Not enough arguments 3'] =
            array($expected, $expectedMessage, $list, $arguments)
        ;

        $list = new ParameterList(array(
            new Parameter(
                'foo',
                new StringType
            ),
            new Parameter(
                'bar',
                new IntegerType
            ),
            new Parameter(
                'baz',
                new FloatType
            ),
        ));
        $arguments = array('foo', 111, 1.11, 2.22);
        $expected = 'InvalidArgumentException';
        $expectedMessage = "Unexpected argument at index 4.";
        $data['Too many arguments'] =
            array($expected, $expectedMessage, $list, $arguments)
        ;

        $list = new ParameterList(array(
            new Parameter(
                'foo',
                new StringType
            ),
            new Parameter(
                'bar',
                new IntegerType
            ),
            new Parameter(
                'baz',
                new FloatType
            ),
        ));
        $arguments = array('foo', 111, 'bar');
        $expected = 'InvalidArgumentException';
        $expectedMessage = "Unexpected argument for parameter 'baz' at index 2.";
        $data['Type mismatch 1'] =
            array($expected, $expectedMessage, $list, $arguments)
        ;

        $list = new ParameterList(array(
            new Parameter(
                'foo',
                new StringType
            ),
            new Parameter(
                'bar',
                new IntegerType
            ),
            new Parameter(
                'baz',
                new FloatType
            ),
        ));
        $arguments = array('foo', 'bar', 'baz');
        $expected = 'InvalidArgumentException';
        $expectedMessage = "Unexpected argument for parameter 'bar' at index 1.";
        $data['Type mismatch 2'] =
            array($expected, $expectedMessage, $list, $arguments)
        ;

        $list = new ParameterList(array(
            new Parameter(
                'foo',
                new StringType
            ),
            new Parameter(
                'bar',
                new IntegerType
            ),
            new Parameter(
                'baz',
                new FloatType
            ),
        ));
        $arguments = array(111, 'bar', 'baz');
        $expected = 'InvalidArgumentException';
        $expectedMessage = "Unexpected argument for parameter 'foo' at index 0.";
        $data['Type mismatch 3'] =
            array($expected, $expectedMessage, $list, $arguments)
        ;

        $list = new ParameterList(
            array(
                new Parameter(
                    'foo',
                    new StringType
                ),
                new Parameter(
                    'bar',
                    new IntegerType
                ),
                new Parameter(
                    'baz',
                    new FloatType,
                    true
                ),
            ),
            true
        );
        $arguments = array('foo', 111, 1.11, 2.22, 'bar', 3.33);
        $expected = 'InvalidArgumentException';
        $expectedMessage = "Unexpected argument for parameter 'baz' at index 4.";
        $data['Variable length type mismatch'] =
            array($expected, $expectedMessage, $list, $arguments)
        ;

        return $data;
    }

    /**
     * @dataProvider parameterListLogicFailureData
     */
    public function testVisitParameterListLogicFailure(
        $expected,
        $expectedMessage,
        ParameterList $list,
        array $arguments
    ) {
        $validatorFixture = $this->validatorFixture($list);

        $this->setExpectedException($expected, $expectedMessage);
        $validatorFixture($arguments);
    }
}
