<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ezzatron\Typhoon\Type;

abstract class BaseType implements Type
{
  /**
   * @param Type $type
   *
   * @return boolean
   */
  public function equalsTyphoonType(Type $type)
  {
    return $this == $type;
  }
}