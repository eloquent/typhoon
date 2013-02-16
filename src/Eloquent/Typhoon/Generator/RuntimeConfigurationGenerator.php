<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Generator;

use Eloquent\Typhoon\Configuration\RuntimeConfiguration;
use Eloquent\Typhoon\TypeCheck\TypeCheck;
use Icecave\Pasta\AST\Expr\Call;
use Icecave\Pasta\AST\Expr\Constant;
use Icecave\Pasta\AST\Expr\Literal;
use Icecave\Pasta\AST\Expr\NewOperator;
use Icecave\Pasta\AST\Expr\QualifiedIdentifier;
use Icecave\Pasta\AST\Expr\StaticMember;
use Icecave\Pasta\AST\Identifier;

class RuntimeConfigurationGenerator
{
    public function __construct()
    {
        $this->typeCheck = TypeCheck::get(__CLASS__, func_get_args());
    }

    /**
     * @param RuntimeConfiguration $configuration
     *
     * @return NewOperator
     */
    public function generate(RuntimeConfiguration $configuration)
    {
        $this->typeCheck->generate(func_get_args());

        $validatorNamespaceCall = new Call(new StaticMember(
            QualifiedIdentifier::fromString('\Eloquent\Cosmos\ClassName'),
            new Constant(new Identifier('fromString'))
        ));
        $validatorNamespaceCall->add(
            new Literal($configuration->validatorNamespace()->string())
        );
        $newConfigurationCall = new Call(QualifiedIdentifier::fromString(
            '\Eloquent\Typhoon\Configuration\RuntimeConfiguration'
        ));
        $newConfigurationCall->add($validatorNamespaceCall);
        $newConfigurationCall->add(
            new Literal($configuration->useNativeCallable())
        );

        return new NewOperator($newConfigurationCall);
    }

    private $typeCheck;
}
