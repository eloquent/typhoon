<?php // @codeCoverageIgnoreStart

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\ClassMapper;

use Eloquent\Typhoon\TypeCheck\TypeCheck;
use Icecave\Pasta\AST\Type\AccessModifier;

class MethodDefinition extends ClassMemberDefinition
{
    /**
     * @param string         $name
     * @param boolean        $isStatic
     * @param boolean        $isAbstract
     * @param AccessModifier $accessModifier
     * @param integer        $lineNumber
     * @param string         $source
     */
    public function __construct(
        $name,
        $isStatic,
        $isAbstract,
        AccessModifier $accessModifier,
        $lineNumber,
        $source
    ) {
        $this->typeCheck = TypeCheck::get(__CLASS__, func_get_args());

        parent::__construct(
            $name,
            $isStatic,
            $accessModifier,
            $lineNumber,
            $source
        );

        $this->isAbstract = $isAbstract;
    }

    /**
     * @return boolean
     */
    public function isAbstract()
    {
        $this->typeCheck->isAbstract(func_get_args());

        return $this->isAbstract;
    }

    private $isAbstract;
    private $typeCheck;
}
