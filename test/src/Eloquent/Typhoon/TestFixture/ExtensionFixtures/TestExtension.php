<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\TestFixture\ExtensionFixtures;

use Eloquent\Typhax\Type\ExtensionType;
use Eloquent\Typhoon\Extension\ExtensionInterface;
use Eloquent\Typhoon\Generator\TyphaxASTGenerator;
use Icecave\Pasta\AST\Expr\Equals;
use Icecave\Pasta\AST\Expr\Literal;
use Icecave\Pasta\AST\Expr\Variable;
use Icecave\Pasta\AST\Func\Closure;
use Icecave\Pasta\AST\Func\Parameter;
use Icecave\Pasta\AST\Identifier;
use Icecave\Pasta\AST\Stmt\ReturnStatement;

class TestExtension implements ExtensionInterface
{
    public function __construct()
    {
        $this->args = func_get_args();
    }

    /**
     * @param TyphaxASTGenerator $generator The AST generator that loaded this extension.
     * @param ExtensionType $type Type extension type for which code should be generated.
     *
     * @return Closure A closure AST node that accepts a single value parameter.
     */
    public function generateTypeCheck(TyphaxASTGenerator $generator, ExtensionType $extensionType)
    {
        $closure = new Closure;
        $closure->addParameter(new Parameter(new Identifier('value')));
        $closure->statementBlock()->add(
            new ReturnStatement(
                new Equals(
                    new Variable(new Identifier('value')),
                    new Literal(12345)
                )
            )
        );

        return $closure;
    }

    public $args;
}
