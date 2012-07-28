<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Type;

use Eloquent\Typhax\IntrinsicType\IntrinsicTypeName;

class NullType implements NamedType
{
  /**
   * @param mixed value
   *
   * @return boolean
   */
  public function typhoonCheck($value)
  {
    return null === $value;
  }

  /**
   * @return string
   */
  public function typhoonName()
  {
    return IntrinsicTypeName::NAME_NULL()->value();
  }
}
