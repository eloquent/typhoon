<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Typhax\Compiler;

use Eloquent\Typhax\AST\Composite;
use Eloquent\Typhax\AST\Type;
use Eloquent\Typhax\IntrinsicType\IntrinsicTypeName;
use PHPUnit_Framework_TestCase;

class StaticPHPCompilerTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->_compiler = new StaticPHPCompiler(
            'foo',
            'bar'
        );
    }

    public function testStringType()
    {
        $type = new Type(
            IntrinsicTypeName::NAME_STRING()->value()
        );
        $expected = <<<EOD
if (!is_string(\$bar)) {
    throw new \InvalidArgumentException("Unexpected argument for foo, expected 'string'.");
}
EOD;

        $this->assertSame($expected, $type->accept($this->_compiler));
    }
}
