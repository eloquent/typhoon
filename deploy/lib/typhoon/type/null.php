<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Typhoon\Type;

use Typhoon\Type;

class Null extends Type
{
  /**
   * @param mixed value
   *
   * @return boolean
   */
  public function check($value)
  {
    return null === $value;
  }
}