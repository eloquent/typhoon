<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Typhoon;

class AndType extends BaseCompositeType
{
  /**
   * @param mixed $value
   *
   * @return boolean
   */
  public function check($value)
  {
    foreach ($this->types as $type)
    {
      if (!$type->check($value))
      {
        return false;
      }
    }

    return true;
  }
}