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

class ArrayType extends SubTyped\BaseTraversableType
{
  /**
   * @return string
   */
  public function typhoonName()
  {
    return IntrinsicTypeName::NAME_ARRAY()->_value();
  }

  /**
   * @param mixed value
   *
   * @return boolean
   */
  protected function checkPrimary($value)
  {
    return is_array($value);
  }
}
