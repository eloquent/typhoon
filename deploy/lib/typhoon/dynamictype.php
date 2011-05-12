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

interface DynamicType extends Type
{
  /**
   * @param string $attribute
   * @param mixed $value
   */
  public function setTyphoonAttribute($attribute, $value);

  /**
   * @return array
   */
  public function typhoonAttributes();
}