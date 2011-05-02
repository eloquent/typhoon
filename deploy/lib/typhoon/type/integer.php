<?php

namespace Typhoon\Type;

use Typhoon\Type;

class Integer extends Type
{
  /**
   * @param mixed value
   * 
   * @return boolean
   */
  public function check($value)
  {
    return is_integer($value);
  }

  /**
   * @return string
   */
  public function __toString()
  {
    return 'integer';
  }
}