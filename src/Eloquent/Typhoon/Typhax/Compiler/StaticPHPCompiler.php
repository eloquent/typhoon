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
use Eloquent\Typhax\AST\Visitor;

class StaticPHPCompiler implements Visitor
{
    public function __construct(
        $parameterName,
        $variableName
    ) {
        $this->parameterName = $parameterName;
        $this->variableName = $variableName;
    }

    /**
     * @param Composite
     *
     * @return string
     */
    public function visitComposite(Composite $composite)
    {

    }

    /**
     * @param Type
     *
     * @return string
     */
    public function visitType(Type $type)
    {
        switch ($type->name()) {
            case IntrinsicTypeName::NAME_STRING()->value():
                $condition = $this->stringTypeCondition();
                break;
            default:
                $condition = 'false';
        }

        return <<<EOD
if (!$condition) {
    throw new \InvalidArgumentException("Unexpected argument for {$this->parameterName}, expected '{$type->name()}'.");
}
EOD
        ;
    }

    /**
     * @return string
     */
    protected function stringTypeCondition()
    {
        return
            'is_string($'.
            $this->variableName.
            ')'
        ;
    }

    private $parameterName;
    private $variableName;
}
