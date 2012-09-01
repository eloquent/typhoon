<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Generator;

use Eloquent\Typhax\Type\AndType;
use Eloquent\Typhax\Type\ArrayType;
use Eloquent\Typhax\Type\BooleanType;
use Eloquent\Typhax\Type\CallableType;
use Eloquent\Typhax\Type\CompositeType;
use Eloquent\Typhax\Type\FloatType;
use Eloquent\Typhax\Type\IntegerType;
use Eloquent\Typhax\Type\MixedType;
use Eloquent\Typhax\Type\NullType;
use Eloquent\Typhax\Type\NumericType;
use Eloquent\Typhax\Type\ObjectType;
use Eloquent\Typhax\Type\OrType;
use Eloquent\Typhax\Type\ResourceType;
use Eloquent\Typhax\Type\StreamType;
use Eloquent\Typhax\Type\StringType;
use Eloquent\Typhax\Type\StringableType;
use Eloquent\Typhax\Type\TraversableType;
use Eloquent\Typhax\Type\TupleType;
use Eloquent\Typhax\Type\Visitor;
use Eloquent\Typhoon\TestCase\MultiGenerationTestCase;
use Icecave\Pasta\AST\Expr\Call;
use Icecave\Pasta\AST\Expr\QualifiedIdentifier;
use Icecave\Pasta\AST\Expr\Variable;
use Icecave\Pasta\AST\Identifier;

class TyphaxASTGeneratorTest extends MultiGenerationTestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->_generator = new TyphaxASTGenerator;
    }

    public function testVisitBooleanType()
    {
        $type = new BooleanType;
        $expected = new Call(QualifiedIdentifier::fromString('\is_bool'));
        $expected->add(new Variable(new Identifier('value')));

        $this->assertEquals($expected, $type->accept($this->_generator));
    }

    public function testVisitCallableType()
    {
        $type = new CallableType;
        $expected = new Call(QualifiedIdentifier::fromString('\is_callable'));
        $expected->add(new Variable(new Identifier('value')));

        $this->assertEquals($expected, $type->accept($this->_generator));
    }
}
