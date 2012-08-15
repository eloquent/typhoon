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

use Eloquent\Typhax\Renderer\TypeRenderer;
use Eloquent\Typhax\Type\FloatType;
use Eloquent\Typhax\Type\IntegerType;
use Eloquent\Typhax\Type\StringType;
use Eloquent\Typhoon\Parameter\Parameter;
use Eloquent\Typhoon\Parameter\ParameterList;
use Phake;
use PHPUnit_Framework_TestCase;

class ParameterListCompilerTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->_typhaxCompiler = new TyphaxCompiler;
        $this->_typeRenderer = new TypeRenderer;
        $this->_compiler = new ParameterListCompiler(
            $this->_typhaxCompiler,
            $this->_typeRenderer
        );
    }

    protected function validatorFixture(ParameterList $parameterList)
    {
        $contents = $parameterList->accept($this->_compiler);
        eval(<<<EOD
use Typhoon\Exception\MissingArgumentException;
use Typhoon\Exception\UnexpectedArgumentException;
use Typhoon\Exception\UnexpectedArgumentValueException;

\$check = function(array \$arguments) {{$contents}};
EOD
);

        return $check;
    }

    public function testConstructor()
    {
        $this->assertSame($this->_typhaxCompiler, $this->_compiler->typhaxCompiler());
        $this->assertSame($this->_typeRenderer, $this->_compiler->typeRenderer());
    }

    public function testConstructorDefaults()
    {
        $this->assertInstanceOf(
            __NAMESPACE__.'\TyphaxCompiler',
            $this->_compiler->typhaxCompiler()
        );
        $this->assertInstanceOf(
            'Eloquent\Typhax\Renderer\TypeRenderer',
            $this->_compiler->typeRenderer()
        );
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
        throw new UnexpectedArgumentValueException('foo', $index, $argument, 'string');
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
        throw new MissingArgumentException('foo', 0, 'string');
    }
    if ($argumentCount < 2) {
        throw new MissingArgumentException('bar', 1, 'integer');
    }
    throw new MissingArgumentException('baz', 2, 'float');
} elseif ($argumentCount > 3) {
    throw new UnexpectedArgumentException(3, $arguments[3]);
}

$check = function($argument, $index) {
    $check = function($value) {
        return is_string($value);
    };
    if (!$check($argument)) {
        throw new UnexpectedArgumentValueException('foo', $index, $argument, 'string');
    }
};
$check($arguments[0], 0);

$check = function($argument, $index) {
    $check = function($value) {
        return is_integer($value);
    };
    if (!$check($argument)) {
        throw new UnexpectedArgumentValueException('bar', $index, $argument, 'integer');
    }
};
$check($arguments[1], 1);

$check = function($argument, $index) {
    $check = function($value) {
        return is_float($value);
    };
    if (!$check($argument)) {
        throw new UnexpectedArgumentValueException('baz', $index, $argument, 'float');
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
                null,
                true
            ),
            new Parameter(
                'baz',
                new FloatType,
                null,
                true
            ),
        ));
        $expected = <<<'EOD'
$argumentCount = count($arguments);
if ($argumentCount < 1) {
    throw new MissingArgumentException('foo', 0, 'string');
} elseif ($argumentCount > 3) {
    throw new UnexpectedArgumentException(3, $arguments[3]);
}

$check = function($argument, $index) {
    $check = function($value) {
        return is_string($value);
    };
    if (!$check($argument)) {
        throw new UnexpectedArgumentValueException('foo', $index, $argument, 'string');
    }
};
$check($arguments[0], 0);

if ($argumentCount > 1) {
    $check = function($argument, $index) {
        $check = function($value) {
            return is_integer($value);
        };
        if (!$check($argument)) {
            throw new UnexpectedArgumentValueException('bar', $index, $argument, 'integer');
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
            throw new UnexpectedArgumentValueException('baz', $index, $argument, 'float');
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
                null,
                true
            ),
            new Parameter(
                'bar',
                new IntegerType,
                null,
                true
            ),
            new Parameter(
                'baz',
                new FloatType,
                null,
                true
            ),
        ));
        $expected = <<<'EOD'
$argumentCount = count($arguments);
if ($argumentCount > 3) {
    throw new UnexpectedArgumentException(3, $arguments[3]);
}

if ($argumentCount > 0) {
    $check = function($argument, $index) {
        $check = function($value) {
            return is_string($value);
        };
        if (!$check($argument)) {
            throw new UnexpectedArgumentValueException('foo', $index, $argument, 'string');
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
            throw new UnexpectedArgumentValueException('bar', $index, $argument, 'integer');
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
            throw new UnexpectedArgumentValueException('baz', $index, $argument, 'float');
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
                    null,
                    true
                ),
            ),
            true
        );
        $expected = <<<'EOD'
$argumentCount = count($arguments);
if ($argumentCount < 2) {
    if ($argumentCount < 1) {
        throw new MissingArgumentException('foo', 0, 'string');
    }
    throw new MissingArgumentException('bar', 1, 'integer');
}

$check = function($argument, $index) {
    $check = function($value) {
        return is_string($value);
    };
    if (!$check($argument)) {
        throw new UnexpectedArgumentValueException('foo', $index, $argument, 'string');
    }
};
$check($arguments[0], 0);

$check = function($argument, $index) {
    $check = function($value) {
        return is_integer($value);
    };
    if (!$check($argument)) {
        throw new UnexpectedArgumentValueException('bar', $index, $argument, 'integer');
    }
};
$check($arguments[1], 1);

if ($argumentCount > 2) {
    $check = function($argument, $index) {
        $check = function($value) {
            return is_float($value);
        };
        if (!$check($argument)) {
            throw new UnexpectedArgumentValueException('baz', $index, $argument, 'float');
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
            throw new UnexpectedArgumentValueException('undefined', $index, $argument, 'mixed');
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
    throw new UnexpectedArgumentException(0, $arguments[0]);
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
                null,
                true
            ),
            new Parameter(
                'baz',
                new FloatType,
                null,
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
                null,
                true
            ),
            new Parameter(
                'bar',
                new IntegerType,
                null,
                true
            ),
            new Parameter(
                'baz',
                new FloatType,
                null,
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
                    null,
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
        $expected = 'Typhoon\Exception\MissingArgumentException';
        $expectedMessage = "Missing argument for parameter 'baz' at index 2. Expected 'float'.";
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
        $expected = 'Typhoon\Exception\MissingArgumentException';
        $expectedMessage = "Missing argument for parameter 'bar' at index 1. Expected 'integer'.";
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
        $expected = 'Typhoon\Exception\MissingArgumentException';
        $expectedMessage = "Missing argument for parameter 'foo' at index 0. Expected 'string'.";
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
        $expected = 'Typhoon\Exception\UnexpectedArgumentException';
        $expectedMessage = "Unexpected argument of type 'float' at index 3.";
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
        $expected = 'Typhoon\Exception\UnexpectedArgumentValueException';
        $expectedMessage = "Unexpected argument of type 'string' for parameter 'baz' at index 2. Expected 'float'.";
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
        $expected = 'Typhoon\Exception\UnexpectedArgumentValueException';
        $expectedMessage = "Unexpected argument of type 'string' for parameter 'bar' at index 1. Expected 'integer'.";
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
        $expected = 'Typhoon\Exception\UnexpectedArgumentValueException';
        $expectedMessage = "Unexpected argument of type 'integer' for parameter 'foo' at index 0. Expected 'string'.";
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
                    null,
                    true
                ),
            ),
            true
        );
        $arguments = array('foo', 111, 1.11, 2.22, 'bar', 3.33);
        $expected = 'Typhoon\Exception\UnexpectedArgumentValueException';
        $expectedMessage = "Unexpected argument of type 'string' for parameter 'baz' at index 4. Expected 'float'.";
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
