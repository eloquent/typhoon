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

class BooleanType extends BaseType
{
  /**
   * @param mixed value
   * 
   * @return boolean
   */
  public function typhoonCheck($value)
  {
    return is_bool($value);
  }

  const ALIAS = 'boolean';
}
