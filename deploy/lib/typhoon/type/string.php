<?php

namespace Typhoon\Type;

use Typhoon\Type;

class String extends Type
{
  /**
   * @param mixed value
   * 
   * @return boolean
   */
  public function check($value)
  {
    return is_string($value);
  }

  /**
   * @return string
   */
  public function __toString()
  {
    return 'string';
  }
}