<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Primitive;

use Eloquent\Typhoon\Type\ClassNameType;

final class ClassName extends Primitive
{
  /**
   * @return ClassNameType
   */
  final public function type()
  {
    return new ClassNameType;
  }
}
