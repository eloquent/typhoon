<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ezzatron\Typhoon\Primitive;

use Ezzatron\Typhoon\Type\CallbackType;

final class Callback extends Primitive
{
  /**
   * @return CallbackType
   */
  final public function type()
  {
    return new CallbackType;
  }
}