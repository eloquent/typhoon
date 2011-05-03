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

use Typhoon\Type\Exception\UnexpectedType;

abstract class Type
{
  /**
   * @param mixed $value
   *
   * @return mixed
   */
  public function assert($value)
  {
    if ($this->check($value)) return $value;
    
    throw new UnexpectedType($value, $this);
  }

  /**
   * @param mixed value
   * 
   * @return boolean
   */
  abstract public function check($value);

  /**
   * @return string
   */
  abstract public function __toString();
}