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

interface Type
{
  /**
   * @param mixed value
   *
   * @return boolean
   */
  public function typhoonCheck($value);

  /**
   * @param Type $type
   *
   * @return boolean
   */
  public function equalsTyphoonType(Type $type);
}