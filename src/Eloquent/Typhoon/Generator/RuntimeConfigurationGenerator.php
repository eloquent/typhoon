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

use Eloquent\Typhoon\Configuration\RuntimeConfiguration;
use Eloquent\Typhoon\TypeCheck\TypeCheck;
use Icecave\Pasta\AST\Expr\Call;
use Icecave\Pasta\AST\Expr\Literal;
use Icecave\Pasta\AST\Expr\NewOperator;
use Icecave\Pasta\AST\Expr\QualifiedIdentifier;

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

        $newConfigurationCall = new Call(QualifiedIdentifier::fromString(
            '\Eloquent\Typhoon\Configuration\RuntimeConfiguration'
        ));
        $newConfigurationCall->add(
            new Literal($configuration->validatorNamespace())
        );
        $newConfigurationCall->add(
            new Literal($configuration->useNativeCallable())
        );

        return new NewOperator($newConfigurationCall);
    }

    private $typeCheck;
}
