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

use Eloquent\Cosmos\ClassName;
use Eloquent\Typhoon\Configuration\RuntimeConfiguration;
use Eloquent\Typhoon\TestCase\MultiGenerationTestCase;
use Icecave\Pasta\AST\Expr\Call;
use Icecave\Pasta\AST\Expr\Constant;
use Icecave\Pasta\AST\Expr\Literal;
use Icecave\Pasta\AST\Expr\NewOperator;
use Icecave\Pasta\AST\Expr\QualifiedIdentifier;
use Icecave\Pasta\AST\Expr\StaticMember;
use Icecave\Pasta\AST\Identifier;

class RuntimeConfigurationGeneratorTest extends MultiGenerationTestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->_generator = new RuntimeConfigurationGenerator;
    }

    public function generateData()
    {
        $data = array();

        $configuration = new RuntimeConfiguration(
            ClassName::fromString('\foo'),
            true
        );
        $validatorNamespaceCall = new Call(new StaticMember(
            QualifiedIdentifier::fromString('\Eloquent\Cosmos\ClassName'),
            new Constant(new Identifier('fromString'))
        ));
        $validatorNamespaceCall->add(new Literal('\foo'));
        $newConfigurationCall = new Call(QualifiedIdentifier::fromString(
            '\Eloquent\Typhoon\Configuration\RuntimeConfiguration'
        ));
        $newConfigurationCall->add($validatorNamespaceCall);
        $newConfigurationCall->add(new Literal(true));
        $expected = new NewOperator($newConfigurationCall);
        $data["Use native callable"] = array($expected, $configuration);

        $configuration = new RuntimeConfiguration(
            ClassName::fromString('\bar'),
            false
        );
        $validatorNamespaceCall = new Call(new StaticMember(
            QualifiedIdentifier::fromString('\Eloquent\Cosmos\ClassName'),
            new Constant(new Identifier('fromString'))
        ));
        $validatorNamespaceCall->add(new Literal('\bar'));
        $newConfigurationCall = new Call(QualifiedIdentifier::fromString(
            '\Eloquent\Typhoon\Configuration\RuntimeConfiguration'
        ));
        $newConfigurationCall->add($validatorNamespaceCall);
        $newConfigurationCall->add(new Literal(false));
        $expected = new NewOperator($newConfigurationCall);
        $data["Don't use native callable"] = array($expected, $configuration);

        return $data;
    }

    /**
     * @dataProvider generateData
     */
    public function testGenerate(NewOperator $expected, RuntimeConfiguration $configuration)
    {
        $this->assertEquals($expected, $this->_generator->generate($configuration));
    }
}
