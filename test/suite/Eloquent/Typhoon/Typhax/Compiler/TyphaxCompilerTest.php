<?php

/*
 * This file is part of the Typhax package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Typhax\Compiler;

use Eloquent\Typhax\Type\AndType;
use Eloquent\Typhax\Type\ArrayType;
use Eloquent\Typhax\Type\BooleanType;
use Eloquent\Typhax\Type\CallbackType;
use Eloquent\Typhax\Type\FloatType;
use Eloquent\Typhax\Type\IntegerType;
use Eloquent\Typhax\Type\MixedType;
use Eloquent\Typhax\Type\NullType;
use Eloquent\Typhax\Type\ObjectType;
use Eloquent\Typhax\Type\OrType;
use Eloquent\Typhax\Type\ResourceType;
use Eloquent\Typhax\Type\StringType;
use Eloquent\Typhax\Type\TraversableType;
use Eloquent\Typhax\Type\TupleType;
use PHPUnit_Framework_TestCase;

class TyphaxCompilerTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->_compiler = new TyphaxCompiler;
    }

    public function testVisitAndType()
    {
        $type = new AndType(array(
            new ObjectType('Foo'),
            new ObjectType('Bar'),
        ));
        $expected = <<<'EOD'
function($value) {
    return (
        call_user_func(
            function($value) {
                return $value instanceof Foo;
            },
            $value
        ) &&
        call_user_func(
            function($value) {
                return $value instanceof Bar;
            },
            $value
        )
    );
}
EOD;

        $this->assertSame($expected, $type->accept($this->_compiler));
    }

    public function testVisitAndTypeEmpty()
    {
        $type = new AndType(array());
        $expected = <<<'EOD'
function($value) {
    return true;
}
EOD;

        $this->assertSame($expected, $type->accept($this->_compiler));
    }

    public function testVisitArrayType()
    {
        $type = new ArrayType;
        $expected = <<<'EOD'
function($value) {
    return is_array($value);
}
EOD;

        $this->assertSame($expected, $type->accept($this->_compiler));
    }

    public function testVisitBooleanType()
    {
        $type = new BooleanType;
        $expected = <<<'EOD'
function($value) {
    return is_bool($value);
}
EOD;

        $this->assertSame($expected, $type->accept($this->_compiler));
    }

    public function testVisitCallbackType()
    {
        $type = new CallbackType;
        $expected = <<<'EOD'
function($value) {
    return is_callable($value);
}
EOD;

        $this->assertSame($expected, $type->accept($this->_compiler));
    }

    public function testVisitFloatType()
    {
        $type = new FloatType;
        $expected = <<<'EOD'
function($value) {
    return is_float($value);
}
EOD;

        $this->assertSame($expected, $type->accept($this->_compiler));
    }

    public function testVisitIntegerType()
    {
        $type = new IntegerType;
        $expected = <<<'EOD'
function($value) {
    return is_integer($value);
}
EOD;

        $this->assertSame($expected, $type->accept($this->_compiler));
    }

    public function testVisitMixedType()
    {
        $type = new MixedType;
        $expected = <<<'EOD'
function($value) {
    return true;
}
EOD;

        $this->assertSame($expected, $type->accept($this->_compiler));
    }

    public function testVisitNullType()
    {
        $type = new NullType;
        $expected = <<<'EOD'
function($value) {
    return $value === null;
}
EOD;

        $this->assertSame($expected, $type->accept($this->_compiler));
    }

    public function testVisitObjectType()
    {
        $type = new ObjectType;
        $expected = <<<'EOD'
function($value) {
    return is_object($value);
}
EOD;

        $this->assertSame($expected, $type->accept($this->_compiler));
    }

    public function testVisitObjectTypeWithTypeOf()
    {
        $type = new ObjectType('Foo\Bar\Baz');
        $expected = <<<'EOD'
function($value) {
    return $value instanceof Foo\Bar\Baz;
}
EOD;

        $this->assertSame($expected, $type->accept($this->_compiler));
    }

    public function testVisitOrType()
    {
        $type = new OrType(array(
            new ObjectType('Foo'),
            new ObjectType('Bar'),
        ));
        $expected = <<<'EOD'
function($value) {
    return (
        call_user_func(
            function($value) {
                return $value instanceof Foo;
            },
            $value
        ) ||
        call_user_func(
            function($value) {
                return $value instanceof Bar;
            },
            $value
        )
    );
}
EOD;

        $this->assertSame($expected, $type->accept($this->_compiler));
    }

    public function testVisitOrTypeEmpty()
    {
        $type = new OrType(array());
        $expected = <<<'EOD'
function($value) {
    return true;
}
EOD;

        $this->assertSame($expected, $type->accept($this->_compiler));
    }

    public function testVisitResourceType()
    {
        $type = new ResourceType;
        $expected = <<<'EOD'
function($value) {
    return is_resource($value);
}
EOD;

        $this->assertSame($expected, $type->accept($this->_compiler));
    }

    public function testVisitStringType()
    {
        $type = new StringType;
        $expected = <<<'EOD'
function($value) {
    return is_string($value);
}
EOD;

        $this->assertSame($expected, $type->accept($this->_compiler));
    }

    public function testTraversableType()
    {
        $type = new TraversableType(
            new ArrayType,
            new StringType,
            new MixedType
        );
        $expected = <<<'EOD'
function($value) {
    if (
        !call_user_func(
            function($value) {
                return is_array($value);
            },
            $value
        )
    ) {
        return false;
    }

    foreach ($value as $key => $subValue) {
        if (
            !call_user_func(
                function($value) {
                    return is_string($value);
                },
                $key
            )
        ) {
            return false;
        }
        if (
            !call_user_func(
                function($value) {
                    return true;
                },
                $subValue
            )
        ) {
            return false;
        }
    }

    return true;
}
EOD;

        $this->assertSame($expected, $type->accept($this->_compiler));
    }

    public function testTupleType()
    {
        $type = new TupleType(array(
            new StringType,
            new IntegerType,
            new NullType,
        ));
        $expected = <<<'EOD'
function($value) {
    if (
        !is_array($value) ||
        array_keys($value) !== range(0, 2)
    ) {
        return false;
    }

    if (
        !call_user_func(
            function($value) {
                    return (
                        call_user_func(
                            function($value) {
                                return is_string($value);
                            },
                            $value[0]
                        ) &&
                        call_user_func(
                            function($value) {
                                return is_integer($value);
                            },
                            $value[1]
                        ) &&
                        call_user_func(
                            function($value) {
                                return $value === null;
                            },
                            $value[2]
                        )
                    );
            },
            $value
        )
    ) {
        return false;
    }

    return true;
}
EOD;

        $this->assertSame($expected, $type->accept($this->_compiler));
    }

    public function testVisitTupleTypeEmpty()
    {
        $type = new TupleType(array());
        $expected = <<<'EOD'
function($value) {
    return $value === array();
}
EOD;

        $this->assertSame($expected, $type->accept($this->_compiler));
    }
}

