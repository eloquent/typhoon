<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Extension;

use Eloquent\Typhax\Type\ExtensionType;
use Eloquent\Typhoon\Generator\TyphaxASTGenerator;
use Icecave\Pasta\AST\Func\Closure;

interface ExtensionInterface
{
    /**
     * @param TyphaxASTGenerator $generator The AST generator that loaded this extension.
     * @param ExtensionType $type Type extension type for which code should be generated.
     *
     * @return Closure A closure AST node that accepts a single value parameter.
     */
    public function generateTypeCheck(TyphaxASTGenerator $generator, ExtensionType $extensionType);
}
