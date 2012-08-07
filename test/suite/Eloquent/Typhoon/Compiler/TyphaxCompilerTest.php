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

use ArrayIterator;
use Eloquent\Typhax\Type\AndType;
use Eloquent\Typhax\Type\ArrayType;
use Eloquent\Typhax\Type\BooleanType;
use Eloquent\Typhax\Type\CallableType;
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
use Eloquent\Typhax\Type\Type;
use Phake;
use PHPUnit_Framework_TestCase;
use stdClass;

class TyphaxCompilerTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->_compiler = new TyphaxCompiler;
    }

    protected function validatorFixture(Type $type)
    {
        eval('$check = '.$type->accept($this->_compiler).';');

        return $check;
    }

    public function testVisitAndType()
    {
        $type = new AndType(array(
            new ObjectType('Foo'),
            new ObjectType('Bar'),
        ));
        $expected = <<<'EOD'
function($value) {
    $check0 = function($value) {
        return $value instanceof Foo;
    };
    $check1 = function($value) {
        return $value instanceof Bar;
    };

    return
        $check0($value) &&
        $check1($value)
    ;
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

    public function testVisitAndTypeLogic()
    {
        $validator = $this->validatorFixture(new AndType(array(
            new ObjectType('Eloquent\Typhax\Type\Visitor'),
            new ObjectType('Phake_IMock'),
        )));

        $this->assertTrue($validator(Phake::mock('Eloquent\Typhax\Type\Visitor')));

        $this->assertFalse($validator(null));
        $this->assertFalse($validator(true));
        $this->assertFalse($validator(false));
        $this->assertFalse($validator(111));
        $this->assertFalse($validator(1.11));
        $this->assertFalse($validator('foo'));
        $this->assertFalse($validator(array()));
        $this->assertFalse($validator(new stdClass));
        $this->assertFalse($validator($this->_compiler));
        $this->assertFalse($validator(stream_context_create()));
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

    public function testVisitArrayTypeLogic()
    {
        $validator = $this->validatorFixture(new ArrayType);

        $this->assertTrue($validator(array()));
        $this->assertTrue($validator(array('foo')));
        $this->assertTrue($validator(array('foo' => 'bar')));

        $this->assertFalse($validator(null));
        $this->assertFalse($validator(true));
        $this->assertFalse($validator(false));
        $this->assertFalse($validator(111));
        $this->assertFalse($validator(1.11));
        $this->assertFalse($validator('foo'));
        $this->assertFalse($validator(new stdClass));
        $this->assertFalse($validator(stream_context_create()));
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

    public function testVisitBooleanTypeLogic()
    {
        $validator = $this->validatorFixture(new BooleanType);

        $this->assertTrue($validator(true));
        $this->assertTrue($validator(false));

        $this->assertFalse($validator(null));
        $this->assertFalse($validator(111));
        $this->assertFalse($validator(1.11));
        $this->assertFalse($validator('foo'));
        $this->assertFalse($validator(array()));
        $this->assertFalse($validator(new stdClass));
        $this->assertFalse($validator(stream_context_create()));
    }

    public function testVisitCallableType()
    {
        $type = new CallableType;
        $expected = <<<'EOD'
function($value) {
    return is_callable($value);
}
EOD;

        $this->assertSame($expected, $type->accept($this->_compiler));
    }

    public function testVisitCallableTypeLogic()
    {
        $validator = $this->validatorFixture(new CallableType);

        $this->assertTrue($validator(function() {}));
        $this->assertTrue($validator('substr'));

        $this->assertFalse($validator(true));
        $this->assertFalse($validator(false));
        $this->assertFalse($validator(null));
        $this->assertFalse($validator(111));
        $this->assertFalse($validator(1.11));
        $this->assertFalse($validator('foo'));
        $this->assertFalse($validator(array()));
        $this->assertFalse($validator(new stdClass));
        $this->assertFalse($validator(stream_context_create()));
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

    public function testVisitFloatTypeLogic()
    {
        $validator = $this->validatorFixture(new FloatType);

        $this->assertTrue($validator(1.11));

        $this->assertFalse($validator(true));
        $this->assertFalse($validator(false));
        $this->assertFalse($validator(null));
        $this->assertFalse($validator(111));
        $this->assertFalse($validator('foo'));
        $this->assertFalse($validator(array()));
        $this->assertFalse($validator(new stdClass));
        $this->assertFalse($validator(stream_context_create()));
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

    public function testVisitIntegerTypeLogic()
    {
        $validator = $this->validatorFixture(new IntegerType);

        $this->assertTrue($validator(111));

        $this->assertFalse($validator(true));
        $this->assertFalse($validator(false));
        $this->assertFalse($validator(null));
        $this->assertFalse($validator(1.11));
        $this->assertFalse($validator('foo'));
        $this->assertFalse($validator(array()));
        $this->assertFalse($validator(new stdClass));
        $this->assertFalse($validator(stream_context_create()));
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

    public function testVisitMixedTypeLogic()
    {
        $validator = $this->validatorFixture(new MixedType);

        $this->assertTrue($validator(true));
        $this->assertTrue($validator(false));
        $this->assertTrue($validator(null));
        $this->assertTrue($validator(111));
        $this->assertTrue($validator(1.11));
        $this->assertTrue($validator('foo'));
        $this->assertTrue($validator(array()));
        $this->assertTrue($validator(new stdClass));
        $this->assertTrue($validator(stream_context_create()));
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

    public function testVisitNullTypeLogic()
    {
        $validator = $this->validatorFixture(new NullType);

        $this->assertTrue($validator(null));

        $this->assertFalse($validator(true));
        $this->assertFalse($validator(false));
        $this->assertFalse($validator(111));
        $this->assertFalse($validator(1.11));
        $this->assertFalse($validator('foo'));
        $this->assertFalse($validator(array()));
        $this->assertFalse($validator(new stdClass));
        $this->assertFalse($validator(stream_context_create()));
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

    public function testVisitObjectTypeLogic()
    {
        $validator = $this->validatorFixture(new ObjectType);

        $this->assertTrue($validator(new stdClass));

        $this->assertFalse($validator(true));
        $this->assertFalse($validator(false));
        $this->assertFalse($validator(null));
        $this->assertFalse($validator(111));
        $this->assertFalse($validator(1.11));
        $this->assertFalse($validator('foo'));
        $this->assertFalse($validator(array()));
        $this->assertFalse($validator(stream_context_create()));
    }

    public function testVisitObjectOfTypeClassLogic()
    {
        $validator = $this->validatorFixture(new ObjectType(
            __NAMESPACE__.'\TyphaxCompiler'
        ));

        $this->assertTrue($validator($this->_compiler));

        $this->assertFalse($validator(true));
        $this->assertFalse($validator(false));
        $this->assertFalse($validator(null));
        $this->assertFalse($validator(111));
        $this->assertFalse($validator(1.11));
        $this->assertFalse($validator('foo'));
        $this->assertFalse($validator(array()));
        $this->assertFalse($validator(new stdClass));
        $this->assertFalse($validator(stream_context_create()));
    }

    public function testVisitObjectOfTypeInterfaceLogic()
    {
        $validator = $this->validatorFixture(new ObjectType(
            'Eloquent\Typhax\Type\Visitor'
        ));

        $this->assertTrue($validator($this->_compiler));

        $this->assertFalse($validator(true));
        $this->assertFalse($validator(false));
        $this->assertFalse($validator(null));
        $this->assertFalse($validator(111));
        $this->assertFalse($validator(1.11));
        $this->assertFalse($validator('foo'));
        $this->assertFalse($validator(array()));
        $this->assertFalse($validator(new stdClass));
        $this->assertFalse($validator(stream_context_create()));
    }

    public function testVisitOrType()
    {
        $type = new OrType(array(
            new ObjectType('Foo'),
            new ObjectType('Bar'),
        ));
        $expected = <<<'EOD'
function($value) {
    $check = function($value) {
        return $value instanceof Foo;
    };
    if ($check($value)) {
        return true;
    }

    $check = function($value) {
        return $value instanceof Bar;
    };
    if ($check($value)) {
        return true;
    }

    return false;
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

    public function testVisitOrTypeLogic()
    {
        $validator = $this->validatorFixture(new OrType(array(
            new ObjectType('Eloquent\Typhax\Type\Visitor'),
            new ObjectType('Phake_IMock'),
        )));

        $this->assertTrue($validator($this->_compiler));
        $this->assertTrue($validator(Phake::mock('Eloquent\Typhax\Type\Visitor')));
        $this->assertTrue($validator(Phake::mock('stdClass')));

        $this->assertFalse($validator(null));
        $this->assertFalse($validator(true));
        $this->assertFalse($validator(false));
        $this->assertFalse($validator(111));
        $this->assertFalse($validator(1.11));
        $this->assertFalse($validator('foo'));
        $this->assertFalse($validator(array()));
        $this->assertFalse($validator(new stdClass));
        $this->assertFalse($validator(stream_context_create()));
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

    public function testVisitResourceTypeOfType()
    {
        $type = new ResourceType('foo');
        $expected = <<<'EOD'
function($value) {
    return
        is_resource($value) &&
        get_resource_type($value) === 'foo'
    ;
}
EOD;

        $this->assertSame($expected, $type->accept($this->_compiler));
    }

    public function testVisitResourceTypeLogic()
    {
        $validator = $this->validatorFixture(new ResourceType);

        $this->assertTrue($validator(stream_context_create()));

        $this->assertFalse($validator(true));
        $this->assertFalse($validator(false));
        $this->assertFalse($validator(null));
        $this->assertFalse($validator(111));
        $this->assertFalse($validator(1.11));
        $this->assertFalse($validator('foo'));
        $this->assertFalse($validator(array()));
        $this->assertFalse($validator(new stdClass));
    }

    public function testVisitResourceTypeOfTypeLogic()
    {
        $file = fopen(__FILE__, 'rb');
        $resourceType = get_resource_type($file);
        $validator = $this->validatorFixture(new ResourceType($resourceType));
        $actual = $validator($file);
        fclose($file);

        $this->assertTrue($actual);

        $this->assertFalse($validator(true));
        $this->assertFalse($validator(false));
        $this->assertFalse($validator(null));
        $this->assertFalse($validator(111));
        $this->assertFalse($validator(1.11));
        $this->assertFalse($validator('foo'));
        $this->assertFalse($validator(array()));
        $this->assertFalse($validator(new stdClass));
        $this->assertFalse($validator(stream_context_create()));
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

    public function testVisitStringTypeLogic()
    {
        $validator = $this->validatorFixture(new StringType);

        $this->assertTrue($validator('foo'));

        $this->assertFalse($validator(true));
        $this->assertFalse($validator(false));
        $this->assertFalse($validator(null));
        $this->assertFalse($validator(111));
        $this->assertFalse($validator(1.11));
        $this->assertFalse($validator(array()));
        $this->assertFalse($validator(new stdClass));
        $this->assertFalse($validator(stream_context_create()));
    }

    public function testVisitTraversableType()
    {
        $type = new TraversableType(
            new ArrayType,
            new StringType,
            new MixedType
        );
        $expected = <<<'EOD'
function($value) {
    $primaryCheck = function($value) {
        return is_array($value);
    };
    if (!$primaryCheck($value)) {
        return false;
    }

    $keyCheck = function($value) {
        return is_string($value);
    };
    $valueCheck = function($value) {
        return true;
    };
    foreach ($value as $key => $subValue) {
        if (!$keyCheck($key)) {
            return false;
        }
        if (!$valueCheck($subValue)) {
            return false;
        }
    }

    return true;
}
EOD;

        $this->assertSame($expected, $type->accept($this->_compiler));
    }

    public function testVisitTraversableTypeLogic()
    {
        $validator = $this->validatorFixture(new TraversableType(
            new ArrayType,
            new StringType,
            new IntegerType
        ));
        $validArray = array(
            'foo' => 111,
            'bar' => 222,
        );
        $partiallyValidArrayValues = array(
            'foo' => 111,
            'bar' => 2.22,
        );
        $partiallyValidArrayKeys = array(
            'foo' => 111,
            222 => 333,
        );
        $invalidPrimaryType = new ArrayIterator(array(
            'foo' => 111,
            'bar' => 222,
        ));

        $this->assertTrue($validator($validArray));
        $this->assertTrue($validator(array()));

        $this->assertFalse($validator($partiallyValidArrayValues));
        $this->assertFalse($validator($partiallyValidArrayKeys));
        $this->assertFalse($validator($invalidPrimaryType));
        $this->assertFalse($validator(null));
        $this->assertFalse($validator(true));
        $this->assertFalse($validator(false));
        $this->assertFalse($validator(111));
        $this->assertFalse($validator(1.11));
        $this->assertFalse($validator('foo'));
        $this->assertFalse($validator(new stdClass));
        $this->assertFalse($validator(stream_context_create()));
    }

    public function testVisitTupleType()
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

    $check0 = function($value) {
        return is_string($value);
    };
    $check1 = function($value) {
        return is_integer($value);
    };
    $check2 = function($value) {
        return $value === null;
    };

    return
        $check0($value[0]) &&
        $check1($value[1]) &&
        $check2($value[2])
    ;
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

    public function testVisitTupleTypeLogic()
    {
        $validator = $this->validatorFixture(new TupleType(array(
            new StringType,
            new IntegerType,
        )));
        $validTuple = array(
            'foo',
            111,
        );
        $partiallyValidTuple = array(
            'foo',
            'bar',
        );
        $invalidTupleNonVector = array(
            1 => 'foo',
            2 => 111,
        );

        $this->assertTrue($validator($validTuple));

        $this->assertFalse($validator($partiallyValidTuple));
        $this->assertFalse($validator($invalidTupleNonVector));
        $this->assertFalse($validator(null));
        $this->assertFalse($validator(true));
        $this->assertFalse($validator(false));
        $this->assertFalse($validator(111));
        $this->assertFalse($validator(1.11));
        $this->assertFalse($validator('foo'));
        $this->assertFalse($validator(new stdClass));
        $this->assertFalse($validator(stream_context_create()));
    }
}
