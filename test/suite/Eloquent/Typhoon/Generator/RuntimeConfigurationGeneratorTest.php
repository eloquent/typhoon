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
use Eloquent\Typhoon\TestCase\MultiGenerationTestCase;
use Icecave\Pasta\AST\Expr\Assign;
use Icecave\Pasta\AST\Expr\Call;
use Icecave\Pasta\AST\Expr\Literal;
use Icecave\Pasta\AST\Expr\NewOperator;
use Icecave\Pasta\AST\Expr\QualifiedIdentifier;

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

        $configuration = new RuntimeConfiguration(true);
        $newConfigurationCall = new Call(QualifiedIdentifier::fromString(
            '\Eloquent\Typhoon\Configuration\RuntimeConfiguration'
        ));
        $newConfigurationCall->add(new Literal(true));
        $expected = new NewOperator($newConfigurationCall);
        $data["Use native callable"] = array($expected, $configuration);

        $configuration = new RuntimeConfiguration(false);
        $newConfigurationCall = new Call(QualifiedIdentifier::fromString(
            '\Eloquent\Typhoon\Configuration\RuntimeConfiguration'
        ));
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
